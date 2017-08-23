<?php

class Mundipagg_ClearsaleController extends Mundipagg_Controller_Abstract {

	public function getConfigAction() {

		if ($this->requestIsValid() == false) {
			echo $this->getResponseForInvalidRequest();
			return false;
		}

		$entityCode = Mage::getStoreConfig('payment/mundipagg_standard/clearsale_entitycode');
		$app = Mage::getStoreConfig('payment/mundipagg_standard/clearsale_app');
		$response = array(
			'entityCode'  => $entityCode,
			'app'       => $app,
			'sessionId' => $this->getSessionId()
		);

		try {
			return $this->jsonResponse($response);

		} catch (Exception $e) {

		}
	}

}