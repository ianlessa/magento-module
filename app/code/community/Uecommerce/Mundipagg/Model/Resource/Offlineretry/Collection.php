<?php

class Uecommerce_Mundipagg_Model_Resource_Offlineretry_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

	protected function _construct() {
		$this->_init('mundipagg/offlineretry');
	}

//	public function addEntityIdFilter($entityId) {
//		$this->addFieldToFilter('entity_id', $entityId);
//
//		return $this;
//	}
//
//	public function addExpiresAtFilter() {
//		$this->addFieldToFilter('expires_at', array('gteq' => date('Y-m-t')));
//
//		return $this;
//	}
}