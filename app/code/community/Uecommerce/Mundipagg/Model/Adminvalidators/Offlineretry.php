<?php

class Uecommerce_Mundipagg_Model_Adminvalidators_Offlineretry extends Mage_Core_Model_Config_Data {

	public function save() {
		$value = $this->getValue();
		$groups = $this->getGroups();
		$forbidPaymentMethods = array(
			'mundipagg_twocreditcards',
			'mundipagg_recurrencepayment'
		);

		if ($value) {
			foreach ($forbidPaymentMethods as $i) {
				$isActive = $groups[$i]['fields']['active']['value'];

				if ($isActive) {
					$helper = Mage::helper('mundipagg');
					$errMsg = $helper->__("Offline retry can't be used with more than 1 creditcard payment method yet. This feature will be available comming soon.");

					Mage::throwException($errMsg);
					break;
				}

			}
		}

		return parent::save();
	}

}