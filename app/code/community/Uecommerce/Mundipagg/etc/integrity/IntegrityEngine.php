<?php

class IntegrityEngine
{

    public function listLogFiles($directories, $logFileConfig) {
        $allLogs = [];
        foreach ($directories as $dir) {
            $allLogs = array_merge($allLogs,$this->listFilesOnDir($dir));
        }
        $allLogs = array_keys($allLogs);
        $allLogs = array_filter($allLogs,function($logFile) use ($logFileConfig) {
            if (substr($logFile, -4) !== '.log') {
                return false;
            }
            $logFileName = explode (DIRECTORY_SEPARATOR,$logFile);
            $logFileName = end($logFileName);

            return
                $logFileName === $logFileConfig['system'] || //magento exception log file
                $logFileName === $logFileConfig['exception'] || //magento system log file
                strpos($logFileName, $logFileConfig['moduleFilenamePrefix']) === 0 ; //module log file.
        });

        return $allLogs;
    }

    private function hasPermissions($file)
    {
        if (!file_exists($file)) {
            echo "<pre>File <strong>'$file'</strong> does not exists!</pre>";
            return false;
        }

        if (!is_readable($file)) {
            echo "<pre>File <strong>'$file'</strong> is not readable!</pre>";
            return false;
        }
        return true;
    }

    public function generateCheckSum($dir)
    {
        if (!is_dir($dir)) {
            return  [
                $dir => file_exists($dir) ? md5_file($dir) : false
            ];
        }

        $files = scandir($dir);
        $md5 = [];
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $file = $dir . DIRECTORY_SEPARATOR . $file;
                $md5[$file] = $this->generateCheckSum($file);
            }
        }
        return $md5;
    }

    public function generateModuleFilesMD5s($modmanFilePath)
    {
        if(!$this->hasPermissions($modmanFilePath)) {
            return [];
        }

        $modmanRawData = file_get_contents($modmanFilePath);
        $rawLines = explode("\n",$modmanRawData);

        $md5s = [];
        foreach ($rawLines as $rawLine) {
            if (
                substr($rawLine,0,1) !== '#' &&
                strlen($rawLine) > 0
            ) {
                $line = array_values(array_filter(explode(' ',$rawLine)));
                if (strpos($line[1], ".modman/") !== 0) { //ignore .modman/*
                    $md5s = array_merge($md5s,$this->filterFileCheckSum(
                        $this->generateCheckSum('./' . $line[1])
                    ));
                }
            }
        }

        return $md5s;
    }

    public function listFilesOnDir($dir) {
        if(!$this->hasPermissions($dir)) {
            return [];
        }

        $rawLines = [$dir];

        $md5s = [];
        foreach ($rawLines as $line) {
            $md5s = array_merge($md5s,$this->filterFileCheckSum(
                $this->generateCheckSum('./' . $line)
            ));
        }

        return $md5s;
    }

    public function filterFileCheckSum($checkSumArray)
    {
        if (count($checkSumArray) === 1) {
            return $checkSumArray;
        }
        $data = serialize($checkSumArray);
        $data = explode('";s:32:"',$data);
        $currentFile = null;
        $currentMd5 = null;
        $files = [];
        foreach ($data as $line) {
            $raw = explode('"',$line);
            if( $currentFile ) {
                $files[$currentFile] = $raw[0];
                $currentFile = end($raw);
                continue;
            }
            $currentFile = end($raw);
        }
        return $files;
    }

    public function verifyIntegrity($modmanFilePath, $integrityCheckFilePath, $ignoreList = [])
    {
        $integrityData = json_decode(file_get_contents($integrityCheckFilePath),true);

        if(!$this->hasPermissions($integrityCheckFilePath)) {
            $integrityData = [];
        }

        $newFiles = [];
        $unreadableFiles = [];
        $alteredFiles = [];

        $files = $this->generateModuleFilesMD5s($modmanFilePath);

        foreach ($ignoreList as $filePath) {
            unset($files[$filePath]);
        }

        //validating files
        foreach ($files as $fileName => $md5) {
            if (substr($fileName, -strlen('integrityCheck')) == 'integrityCheck') {
                //skip validation of integrityCheck file
                continue;
            }
            if ($md5 === false) {
                $unreadableFiles[] = $fileName;
                continue;
            }
            if(isset($integrityData[$fileName])) {
                if ($md5 != $integrityData[$fileName]) {
                    $alteredFiles[] = $fileName;
                }
                continue;
            }
            $newFiles[$fileName] = $md5;
        }

        return [
            'files' => $files,
            'newFiles' => $newFiles,
            'unreadableFiles' => $unreadableFiles,
            'alteredFiles' => $alteredFiles
        ];
    }
}