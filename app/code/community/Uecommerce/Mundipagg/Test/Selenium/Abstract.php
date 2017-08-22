<?php


class Uecommerce_Mundipagg_Test_Selenium_Abstract extends PHPUnit_Extensions_Selenium2TestCase {

    public $_installmentActive;
    public $_Recurrency;
    public $_paymentType;
    public $_isLogged;
    public $_isPj;
    protected $_additionalSaveSettings = array();
    protected static $_custmerTest = array(
        'firstname' => 'Test',
        'lastname' => 'Test',
        'email' => 'test@testmundipagg.com.br',
        'street1' => 'Rua da Quitanda',
        'city' => 'Rio de Janeiro',
        'country_id' => 'BR',
        'postcode' => '20091005',
        'telephone' => '2135542800',
        'customer_password' => 'mundipagg123',
        'confirm_password' => 'mundipagg123',
        'taxvat' => '54201288102',
        'region' => 'RJ'
    );

    protected static $_customerPjTest = array(
        'firstname' => 'Test',
        'lastname' => 'Test',
        'email' => 'test@testmundipagg.com.br',
        'street1' => 'Rua da Quitanda',
        'city' => 'Rio de Janeiro',
        'country_id' => 'BR',
        'postcode' => '20091005',
        'telephone' => '2135542800',
        'customer_password' => 'mundipagg123',
        'confirm_password' => 'mundipagg123',
        'taxvat' => '61181731000175',
        'company' => 'Test',
        'region' => 'RJ'
    );

    protected static $_disableCaches;
    protected static $_setConfigMagento;
    protected static $_createProduct;
    protected static $_productSku = 'test';
    protected static $_defaultSleep = 10;
    protected $_envCI = 'MAGE';

    public function setUp(){
        parent::setUp();

        $this->setBrowser('firefox');
        $this->setBrowserUrl(Mage::getBaseUrl());
        $this->setUpSessionStrategy(null);

        // Default Browser-Size
        $this->prepareSession()->currentWindow()->size(array('width' => 1280, 'height' => 1024));
        
        
        self::initFrontend(1);

        if(Mage::getIsDeveloperMode()){
            self::$_defaultSleep = 4;
        }

        $this->disableCaches();
        $this->setConfigMagento();
        $this->setMundipaggConfig();
        $this->createSimpleProduct();
        shell_exec('echo "" > ../var/log/Uecommerce_Mundipagg.log');
        
    }

    /**
     * Set default settings Mundipagg
     */
    public function setMundipaggConfig() {
        $config = $this->getConfig();

        $config->saveConfig('payment/mundipagg_standard/merchantKeyStaging', '5efae21a-3ce0-4a63-884a-b8b6fb6ad1e3');
        $config->saveConfig('payment/mundipagg_standard/payment_action', 'authorize');
        $config->saveConfig('payment/mundipagg_standard/cc_types', 'VI,MC,AE,DI,EL,HI');
        if ($this->_installmentActive) {
            $config->saveConfig('payment/mundipagg_standard/enable_installments', '1');
            $config->saveConfig('payment/mundipagg_standard/display_total', '1');
            $config->saveConfig('payment/mundipagg_standard/installments', 'a:4:{i:0;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:0:"";}i:1;a:3:{i:0;s:0:"";i:1;s:1:"2";i:2;s:0:"";}i:2;a:3:{i:0;s:0:"";i:1;s:1:"3";i:2;s:1:"5";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"5";i:2;s:2:"10";}}');
            $config->saveConfig('payment/mundipagg_standard/installments_VI', 'a:4:{i:0;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:0:"";}i:1;a:3:{i:0;s:0:"";i:1;s:1:"2";i:2;s:0:"";}i:2;a:3:{i:0;s:0:"";i:1;s:1:"3";i:2;s:1:"5";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"5";i:2;s:2:"10";}}');
            $config->saveConfig('payment/mundipagg_standard/installments_MC', 'a:4:{i:0;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:0:"";}i:1;a:3:{i:0;s:0:"";i:1;s:1:"2";i:2;s:0:"";}i:2;a:3:{i:0;s:0:"";i:1;s:1:"3";i:2;s:1:"5";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"5";i:2;s:2:"10";}}');
            $config->saveConfig('payment/mundipagg_standard/installments_AE', 'a:4:{i:0;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:0:"";}i:1;a:3:{i:0;s:0:"";i:1;s:1:"2";i:2;s:0:"";}i:2;a:3:{i:0;s:0:"";i:1;s:1:"3";i:2;s:1:"5";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"5";i:2;s:2:"10";}}');
            $config->saveConfig('payment/mundipagg_standard/installments_DI', 'a:4:{i:0;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:0:"";}i:1;a:3:{i:0;s:0:"";i:1;s:1:"2";i:2;s:0:"";}i:2;a:3:{i:0;s:0:"";i:1;s:1:"3";i:2;s:1:"5";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"5";i:2;s:2:"10";}}');
            $config->saveConfig('payment/mundipagg_standard/installments_EL', 'a:4:{i:0;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:0:"";}i:1;a:3:{i:0;s:0:"";i:1;s:1:"2";i:2;s:0:"";}i:2;a:3:{i:0;s:0:"";i:1;s:1:"3";i:2;s:1:"5";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"5";i:2;s:2:"10";}}');
            $config->saveConfig('payment/mundipagg_standard/installments_HI', 'a:4:{i:0;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:0:"";}i:1;a:3:{i:0;s:0:"";i:1;s:1:"2";i:2;s:0:"";}i:2;a:3:{i:0;s:0:"";i:1;s:1:"3";i:2;s:1:"5";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"5";i:2;s:2:"10";}}');
        } else {
            $config->saveConfig('payment/mundipagg_standard/enable_installments', '0');
        }

        if(is_array($this->_additionalSaveSettings) && count($this->_additionalSaveSettings)){
            foreach($this->_additionalSaveSettings as $path => $value){
                $config->saveConfig($path, $value);
            }
        }

        $config->reinit();
        $config->cleanCache();
        Mage::app()->reinitStores();
        Mage::app()->getCacheInstance()->cleanType('config');
    }

    /**
     * Set default settings Magento
     */
    public function setConfigMagento() {
        if(self::$_setConfigMagento){
            return false;
        }
        $config = $this->getConfig();
        $config->saveConfig('dev/template/allow_symlink', '1');
        $config->saveConfig('dev/log/active', '1');
        $config->saveConfig('customer/address/taxvat_show', 'opt');
        $config->reinit();
        $config->cleanCache();
        Mage::app()->reinitStores();
        self::$_setConfigMagento = true;
    }

   

    /**
     * Create a simple product to test
     */
    public function createSimpleProduct() {
        if(self::$_createProduct){
            return false;
        }
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        
        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product');
        $product->load('sku', 'test');
        if ($product->getId()) {
            return false;
        }

        try {
            $product
                    ->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
                    ->setAttributeSetId(Mage::getModel('catalog/product')->getDefaultAttributeSetId()) //ID of a attribute set named 'default'
                    ->setTypeId('simple') //product type
                    ->setCreatedAt(strtotime('now')) //product creation time
                    ->setSku(self::$_productSku) //SKU
                    ->setName('Test') //product name
                    ->setWeight(1.0000)
                    ->setStatus(1) //product status (1 - enabled, 2 - disabled)
                    ->setTaxClassId(0) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                    ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) //catalog and search visibility
                    //->setNewsFromDate('06/26/2014') //product set as new from
                    //->setNewsToDate('06/30/2014') //product set as new to
                    ->setPrice(11.22) //price in form 11.22
                    ->setCost(11.22) //price in form 11.22
                    //->setSpecialPrice(00.44) //special price in form 11.22
                    // ->setSpecialFromDate('06/1/2014') //special price from (MM-DD-YYYY)
                    //->setSpecialToDate('06/30/2014') //special price to (MM-DD-YYYY)
                    ->setDescription('This is a long description')
                    ->setShortDescription('This is a short description')
                    ->setStockData(array(
                        'use_config_manage_stock' => 0, //'Use config settings' checkbox
                        'manage_stock' => 0, //manage stock
//                        'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
//                        'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
//                        'is_in_stock' => 1, //Stock Availability
//                        'qty' => 999 //qty
                            )
            );
            //->setCategoryIds(array(3, 10)); //assign product to categories
            $product->save();
            self::$_createProduct = true;
//endif;
        } catch (Exception $e) {
            self::$_createProduct = false;
            Mage::log($e->getMessage());
        }
    }
    
    
    /**
     * Disable all Magento caches
     * 
     * @return boolean
     */
    public function disableCaches(){
        if(self::$_disableCaches){
            return false;
        }
        /** @var $model Mage_Core_Model_Cache */
        $model = Mage::getModel('core/cache');
        $options = $model->canUse();
        foreach($options as $option => $value){
            $options[$option] = 0;
        }
        $model->saveOptions($option);
        self::$_disableCaches = true;
    }
    
    
    
    public function runMundipagg(){
        //Mage::log(Mage::app()->getResponse());
        if(!$this->_isLogged){
            $this->deleteCustomerIfExists();
        }

        $customer = $this->getCustomer();
        Mage::getConfig()->saveConfig('mundipagg_tests_cpf_cnpj', $customer['taxvat']);
        Mage::getConfig()->reinit();
        Mage::getConfig()->cleanCache();
        Mage::app()->reinitStores();

        // Access homePage
        $this->url(Mage::getBaseUrl());
        $customer = $this->getCustomer();

        $this->assertEquals(200, $this->getHttpCode());
        sleep(self::$_defaultSleep);

        // Search product
        $element = $this->byId('search');
        $element->value(self::$_productSku);
        $this->clickButtonByContainer('search_mini_form');
        $this->assertContains('?q=' . self::$_productSku, $this->url());
        // Add product to cart
        $productsByList = $this->findElementsByCssSelector('.btn-cart');
        foreach ($productsByList as $btn) {
            if ($btn->displayed()) {
                $btn->click();
            }
        }
        $this->assertContains('checkout/cart',$this->url());
        // Go to checkout
        $this->byCssSelector('.btn-proceed-checkout')->click();
        $this->assertContains('checkout/onepage/', $this->url());
        //Customer onepage
        if(!$this->_isLogged){
            $this->registerCustomer();
        }else{
            $this->loginCustomer();
        }
        
        
    }
    
    /**
     * Selenium register Customer
     */
    public function registerCustomer(){
        $customer = $this->getCustomer();
        $this->byId('login:register')->click();
        $this->byId('onepage-guest-register-button')->click();
        sleep(self::$_defaultSleep);
        $this->byId('billing:firstname')->value($customer['firstname']);
        $this->byId('billing:lastname')->value($customer['lastname']);
        $this->byId('billing:email')->value($customer['email']);
        $this->byId('billing:street1')->value($customer['street1']);
        $this->byId('billing:city')->value($customer['city']);
        $this->byId('billing:country_id')->value($customer['country_id']);
        if($this->byName('billing[region]')->displayed()) {
            $this->byId('billing:region')->value($customer['region']);
        }
        $this->byId('billing:postcode')->value($customer['postcode']);
        $this->byId('billing:telephone')->value($customer['telephone']);
        $this->byId('billing:customer_password')->value($customer['customer_password']);
        $this->byId('billing:confirm_password')->value($customer['confirm_password']);
        /*if($this->byName('billing[taxvat]')->displayed()) {
            $this->byId('billing:taxvat')->value($customer['taxvat']);
        }*/
        $this->clickButtonByContainer('billing-buttons-container');

        sleep(self::$_defaultSleep);
        //$this->assertElementHasClass('active', $this->byId('opc-shipping_method'));
        sleep(self::$_defaultSleep);
    }

    /**
     * Login Customer
     */
    public function loginCustomer(){
        $this->byId('login-email')->value(self::$_custmerTest['email']);
        $this->byId('login-password')->value(self::$_custmerTest['customer_password']);
        $this->byId('login-form')->submit();
        sleep(self::$_defaultSleep);
        $this->assertElementHasClass('active', $this->byId('opc-billing'));
        $this->clickButtonByContainer('billing-buttons-container');
        sleep(self::$_defaultSleep);
        $this->assertElementHasClass('active', $this->byId('opc-shipping_method'));
        
        sleep(self::$_defaultSleep);
        
    }
    
    /**
     * Delete Test customer
     * 
     * @return boolean
     */
    public function deleteCustomerIfExists(){
        $website = Mage::app()->getWebsite()->getId();
        
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId(($website?$website:1));
        $customer->loadByEmail(self::$_custmerTest['email']);
        
        if ($customer->getId()) {
            $customer->setIsDeleteable(true);
            $customer->delete();
            return true;
        }else{
            return false;
        }
    }
    
     /**
     * @return PHPUnit_Extensions_Selenium2TestCase_Element 
     */
    public function findElementsByCssSelector($selector, \PHPUnit_Extensions_Selenium2TestCase_Element $root_element = null) {
        if (!$root_element) {
            $root_element = $this;
        }
        return $root_element->elements($this->using('css selector')->value($selector));
    }

    /**
     * Performs a click on the child element the id of the last container as a parameter
     * 
     * @param string $container
     */
    public function clickButtonByContainer($container) {
        $buttons = $this->findElementsByCssSelector('.button', $this->byId($container));
        foreach ($buttons as $b) {
            if ($b->displayed()) {
                $b->click();
            }
        }
    }
    
    /**
     * Get Http Response Code
     * 
     * @return int
     */
    public function getHttpCode(){
        return Mage::app()->getResponse()->getHttpResponseCode();
    }
    
     /**
     * @param $class
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function assertElementHasClass( $class, \PHPUnit_Extensions_Selenium2TestCase_Element $element )
    {
        $classes = explode(' ', $element->attribute('class') );
        $this->assertContains($class, $classes);
    }
    
    protected function tearDown(){
        //$this->deleteCustomerIfExists();
        print shell_exec('cat ../var/log/Uecommerce_Mundipagg.log');
        
    }
    public static function initFrontend($code = null)
    {
        if ($code === null) {
            $code = self::getArg('store_code', '');
        }
        self::init($code);

        Mage::register('isSecureArea', true, true);

        
        Mage::app()->loadArea(Mage_Core_Model_App_Area::AREA_FRONTEND);

        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            Mage_Core_Model_App_Area::AREA_FRONTEND,
            true
        );
    }

    protected static function init($code)
    {
        Mage::$headersSentThrowsException = false;

//        $options = array();
//        $options['config_model'] = 'Codex_Xtest_Model_Core_Config';
//        $options['cache_dir'] = Mage::getBaseDir('var').DS.'cache'.DS.'xtest';

        Mage::reset();
        Mage::app($code, 'store');

//        if ($disableDouble = (bool)self::getArg('disable_double', false)) {
//            self::getConfig()->setDisableDoubles($disableDouble);
//        }
    }

    /**
     * @return Mage_Core_Model_Config
     */
    public static function getConfig()
    {
        return Mage::getConfig();
    }
    
    public function selectOptionByValue(PHPUnit_Extensions_Selenium2TestCase_Element $element, $value){
        PHPUnit_Extensions_Selenium2TestCase_Element_Select::fromElement($element)->selectOptionByValue($value);
    }

    public function getCustomer()
    {
        if($this->_isPj) {
            $customer = self::$_customerPjTest;
        } else {
            $customer = self::$_custmerTest;
        }

        return $customer;
    }

    public function execSuccessTest($url = false)
    {
        if(!$url) {
            $url = 'mundipagg/standard/success';
        }
        if(!getenv($this->_envCI)) {
            sleep(self::$_defaultSleep+20);
            $this->assertContains($url,$this->url());
        }
    }
}
