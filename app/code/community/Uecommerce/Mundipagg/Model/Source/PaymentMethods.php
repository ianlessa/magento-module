<?php
/**
 * Uecommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Uecommerce EULA.
 * It is also available through the world-wide-web at this URL:
 * http://www.uecommerce.com.br/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.uecommerce.com.br/ for more information
 *
 * @category   Uecommerce
 * @package    Uecommerce_Mundipagg
 * @copyright  Copyright (c) 2012 Uecommerce (http://www.uecommerce.com.br/)
 * @license    http://www.uecommerce.com.br/
 */

/**
 * Mundipagg Payment module
 *
 * @category   Uecommerce
 * @package    Uecommerce_Mundipagg
 * @author     Uecommerce Dev Team
 */

class Uecommerce_Mundipagg_Model_Source_PaymentMethods
{
    public function toOptionArray() 
    {
        return array(
            array(
            	'value' => 'BoletoBancario', 
            	'label' => Mage::helper('mundipagg')->__('Boleto Bancário')
            ),
            array(
            	'value' => '1CreditCards', 
            	'label' => Mage::helper('mundipagg')->__('1 Credit Card')
            ),
            array(
                'value' => '2CreditCards', 
                'label' => Mage::helper('mundipagg')->__('2 Credit Cards')
            )
        );
    }
}