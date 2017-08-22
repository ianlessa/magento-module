<?php

class Uecommerce_Mundipagg_Test_Selenium_CcTypes extends Uecommerce_Mundipagg_Test_Selenium_Abstract {

	/**
	 * Fake credit cards informations to test
	 *
	 * @var array
	 */
	public $_ccCards;
	public $_isCardonfile;
	public $_paymentType;
	public $_ccLength;
	public $_values;

	public function setUp() {
		$this->_installmentActive = false;
		$this->_additionalSaveSettings = array(
			'payment/mundipagg_creditcardoneinstallment/active' => 1,
			'payment/mundipagg_creditcard/active'               => 1,
			'payment/mundipagg_twocreditcards/active'           => 1,
		);

		$this->setAllFakeCc();

		parent::setUp();
	}

	/**
	 * Test all ccTypes validations
	 */
	public function runAllCcFlagsValidations() {
		$this->runMundipagg();
		$this->clickButtonByContainer('shipping-method-buttons-container');
		sleep(self::$_defaultSleep);
		$this->byId('p_method_mundipagg_' . $this->_paymentType)->click();

		$ccLengthRef = $this->_ccLength;

		if (empty($ccLengthRef)) {
			$this->_ccLength = 1;
		}

		for ($i = 1; $i <= $this->_ccLength; $i++) {
			$flags = $this->findElementsByCssSelector('.cc_brand_types', $this->byId('mundipagg_' . $this->_paymentType . '_new_credit_card_' . $this->_ccLength . '_' . $i));
			foreach ($this->_ccCards as $flag => $card) {
				foreach ($card as $key => $number) {
					$this->byId('mundipagg_' . $this->_paymentType . '_' . $this->_ccLength . '_' . $i . '_cc_number')->value($this->_ccCards[$flag][$key][0]);
					foreach ($flags as $element) {
						if (strpos($element->attribute('class'), strtolower($flag)) !== false) {
							sleep(1);
							$this->assertContains('active', $element->attribute('class'));
							$this->byId('mundipagg_' . $this->_paymentType . '_' . $this->_ccLength . '_' . $i . '_cc_number')->clear();
						}
					}
				}
			}
			$this->byId('mundipagg_' . $this->_paymentType . '_' . $this->_ccLength . '_' . $i . '_cc_number')->clear();
			$ccRand = $this->getCcRand();
			$this->byId('mundipagg_' . $this->_paymentType . '_' . $this->_ccLength . '_' . $i . '_cc_number')->value($ccRand[0]);
			$this->byId('mundipagg_' . $this->_paymentType . '_cc_holder_name_' . $this->_ccLength . '_' . $i)->value(self::$_custmerTest['firstname']);
			$this->selectOptionByValue($this->byId('mundipagg_' . $this->_paymentType . '_expirationMonth_' . $this->_ccLength . '_' . $i), 06);
			$this->selectOptionByValue($this->byId('mundipagg_' . $this->_paymentType . '_expirationYear_' . $this->_ccLength . '_' . $i), 25);
			$this->byId('mundipagg_' . $this->_paymentType . '_cc_cid_' . $this->_ccLength . '_' . $i)->value($ccRand[1]);

			//TODO Implement Tests for card on file.
			//$this->byId('mundipagg_' . $this->_paymentType . '_save_token_' . $this->_ccLength . '_' . $i)->click();
		}
		$this->setValues();
	}

	public function runCardonfile() {
		$this->runMundipagg();
		if ($this->_isCardonfile) {
			for ($i = 1; $i < $this->_ccLength; $i++) {
				$cardonFiles = $this->byCssSelector('select#mundipagg_twocreditcards_token_' . $this->_ccLength . '_' . $i . ' > option');

				$this->selectOptionByValue($this->byId('mundipagg_twocreditcards_token_' . $this->_ccLength . '_' . $i), $cardonFiles[1]->value());
			}
		}
		$this->clickButtonByContainer('shipping-method-buttons-container');
		sleep(self::$_defaultSleep);
		$this->byId('p_method_mundipagg_' . $this->_paymentType)->click();
		$this->setValues();
	}

	public function continueBuy() {
		$this->clickButtonByContainer('payment-buttons-container');
		sleep(self::$_defaultSleep);
		$this->clickButtonByContainer('review-buttons-container');
		$this->execSuccessTest();
	}

	public function setValues() {
		if (is_array($this->_values) && count($this->_values)) {
			foreach ($this->_values as $input => $value) {
				$this->byId('mundipagg_' . $this->_paymentType . '_new_value_' . $this->_ccLength . '_' . $input)->value($value);
			}
		} else {
			return false;
		}
	}

	/**
	 * Set all fake Cc
	 */
	public function setAllFakeCc() {
		$this->_ccCards = array(
			'VI' => array(
				// Credit card number    // Verification Number
				array('4539237284301694', '123'),
//                array('4485024757890740', '123'),
//                array('4970582526036384', '123'),
//                array('4716815682675549', '123'),
//                array('4218703979907168', '123'),
			),
			'MC' => array(
				array('5208217933877887', '123'),
//                array('5575900683301001', '123'),
//                array('5303567141573171', '123'),
//                array('5368614439596803', '123'),
//                array('5208793547521723', '123'),
			),
			'DI' => array(
				array('30151661373832', '123'),
//                array('36551150116846', '123'),
//                array('30008112964538', '123'),
//                array('30110260186607', '123'),
			),
			'AE' => array(
				array('377422011608347', '1234'),
//                array('370935808314404', '1234'),
//                array('349750622252286', '1234'),
//                array('375588571162990', '1234'),
			),
			'HI' => array(
				array('6062828614827141', '123'),
//                array('6062829471639405', '123'),
//                array('6062828961022288', '123'),
//                array('6062825624254001', '123'),
//                array('3841001111222233334', '123')
			),
			'EL' => array(
				array('6362974242267115', '123'),
				array('4011783039660247', '123'),
				array('6363683924722462', '123'),
				array('4011798939333205', '123'),
			)
		);
	}

	/**
	 * Get
	 *
	 * @return array
	 */
	public function getCcRand() {
		$total = (count($this->_ccCards) - 1);
		$nrand = rand(0, $total);
		if ($nrand == $total) {
			$nrand = 1;
		}
		$currentRand = reset(array_slice($this->_ccCards, $nrand, -($total - $nrand)));

		return $currentRand[0];
	}

	protected function deleteAllCardonfiles() {
		$customer = Mage::getModel("customer/customer");
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
		if ($customer->loadByEmail(parent::$_custmerTest['email'])->getId()) {
			$ccsCollection = Mage::getResourceModel('mundipagg/cardonfile_collection')
				->addEntityIdFilter($customer->getId());
			foreach ($ccsCollection as $cardonfile) {
				$cardonfile->delete();
			}
		}
	}

	protected function tearDown() {
//        if ($this->_isLogged) {
//            $this->deleteAllCardonfiles();
//        }
		parent::tearDown();
	}

	protected function runProcess() {
		$this->runAllCcFlagsValidations();
		$this->continueBuy();
	}

	/**
	 * To set values to split cards.
	 *
	 * @param int $n
	 */
	protected function setCCValues($n = 2) {
		switch ($n) {
			case 2:
				$this->_values = array(
					1 => '5',
//                  2 => '6,22'
				);
				break;
			case 3:
				$this->_values = array(
					1 => '5,61',
					2 => '5,61',
					3 => '5,00'
				);
				break;
			case 4:
				$this->_values = array(
					1 => '3,74',
					2 => '3,74',
					3 => '3,74',
					4 => '5,00'
				);
				break;
			case 5:
				$this->_values = array(
					1 => '2,22',
					2 => '3,00',
					3 => '3,00',
					4 => '3,00',
					5 => '5,00'
				);
				break;
		}
	}

}
