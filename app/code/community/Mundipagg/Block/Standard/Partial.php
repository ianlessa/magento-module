<?php
/**
 * MundiPagg Embeddables
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MundiPagg.
 * It is also available through the world-wide-web at this URL:
 * http://www.mundipagg.com/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mundipagg.com/ for more information
 *
 * @category   MundiPagg
 * @package    Mundipagg
 * @copyright  Copyright (c) 2017 MundiPagg(http://www.mundipagg.com/)
 * @license    http://www.mundipagg.com/
 */

/**
 * Mundipagg Payment module
 *
 * @category   MundiPagg
 * @package    Mundipagg
 * @author     MundiPagg Embeddables Team
 */

class Mundipagg_Block_Standard_Partial extends Mage_Checkout_Block_Onepage_Payment_Methods
{
	/**
     * Internal constructor
     * Set template for redirect
     *
     */
	public function __construct() 
	{
		parent::_construct();

        $this->setTemplate('mundipagg/partial.phtml');

        // Get Customer Credit Cards Saved On File
        if ($this->helper('customer')->isLoggedIn()) {
            $entityId = Mage::getSingleton('customer/session')->getId();

            $ccsCollection = Mage::getResourceModel('mundipagg/cardonfile_collection')
                ->addEntityIdFilter($entityId)
                ->addExpiresAtFilter();

            $this->setCcs($ccsCollection);
        } else {
            $this->setCcs(array());
        }
    }

    /**
     * Initialize data and prepare it for output
     */
    protected function _beforeToHtml()
    {
        //return parent::_beforeToHtml();
    }

    /**
     * Return Standard model
     */
    public function getStandard() 
    {
        return Mage::getModel('mundipagg/standard');
    }

    /**
     * Return creditcard model
     */
    public function getCreditcard() 
    {
        return Mage::getModel('mundipagg/creditcard');
    }

    /**
     * Return twocreditcards model
     */
    public function getTwocreditcards() 
    {
        return Mage::getModel('mundipagg/twocreditcards');
    }

    /**
     * Return threecreditcards model
     */
    public function getThreecreditcards() 
    {
        return Mage::getModel('mundipagg/threecreditcards');
    }

    /**
     * Return fourcreditcards model
     */
    public function getFourcreditcards() 
    {
        return Mage::getModel('mundipagg/fourcreditcards');
    }

    /**
     * Return fivecreditcards model
     */
    public function getFivecreditcards() 
    {
        return Mage::getModel('mundipagg/fivecreditcards');
    }
    
    /**
     * Return recurrence payment model
     */
    public function getRecurrencePayment()
    {
        return Mage::getModel('mundipagg/recurrencepayment');
    }

    /**
    * Get last quote that is in session
    */
    public function getQuote()
    {
        $lastQuoteId = Mage::getSingleton('checkout/session')->getLastSuccessQuoteId();

        return Mage::getModel('sales/quote')->load($lastQuoteId);
    }

    /**
    * Get last order that is in session
    */
    public function getOrder()
    {
        $session = Mage::getSingleton('checkout/session');

        $session->setQuoteId($session->getMundipaggStandardQuoteId(true));

        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());

            return $order;
        }
    }

    /**
    * Get last order baseGrandTotal that is in session
    */
    public function getQuoteBaseGrandTotal()
    {
        return $this->getOrder()->getBaseGrandTotal();
    }

    /**
     * Retrieve availale payment methods
     *
     * @return array
     */
    public function getMethods()
    {
        $methods = $this->getData('methods');
        if (is_null($methods)) {
            $quote = $this->getQuote();
            $store = $quote ? $quote->getStoreId() : null;
            $methods = $this->helper('payment')->getStoreMethods($store, $quote);
            $total = $quote->getBaseSubtotal() + $quote->getShippingAddress()->getBaseShippingAmount();

            foreach ($methods as $key => $method) {
                if ($this->_canUseMethod($method)
                    && ($total != 0
                        || $method->getCode() == 'free'
                        || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles()))) {
                    $this->_assignMethod($method);
                } else {
                    unset($methods[$key]);
                }
            }
            $this->setData('methods', $methods);
        }
        return $methods;
    }
}
