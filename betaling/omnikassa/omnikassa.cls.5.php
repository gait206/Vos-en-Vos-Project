<?php

	/*
		- NL-NL -
		PHP5 bibliotheek om een betaalverzoek te starten. 
		Berekend en controleerd de beveiligings hash.


		- EN-US -
		PHP5 Class to generate an OmniKassa form for a payment request. 
		Also calculates and verifies your security hashcode.
	*/

	class OmniKassa
	{
		// Default settings
		protected $sCurrencyCode = 'EUR';
		protected $sLanguageCode = 'nl'; // NL
		protected $aPaymentMethods = array('IDEAL');


		// Account settings
		protected $sMerchantId = '';
		protected $sSubId = '0';
		protected $sSecurityKey = '';
		protected $sSecurityKeyVersion = '1';

		protected $sAquirerUrl = 'https://payment-webinit.omnikassa.rabobank.nl/paymentServlet';
		protected $bTestMode = false;


		// Order settings
		protected $fOrderAmount = 0.00;
		protected $sOrderId = '';
		protected $sTransactionReference = false;


		// URL settings
		protected $sReturnUrl = 'http://localhost:8080/betaling/return.php';
		protected $sReportUrl = '';


		// Form settings
		protected $sButtonLabel = 'Afrekenen';
		protected $sButtonImage = false;
		protected $iButtonImageWidth = 0;
		protected $iButtonImageHeight = 0;


		// Default constructor when calling new OmniKassa()
		public function __construct($bTestMode = false)
		{
			if($bTestMode)
			{
				$this->setTestMode();
			}
		}


		// Set payment type ideal|minitix|visa|mastercard|maestro|incasso|acceptgiro|rembours
		public function setPaymentMethod($sPaymentMethods)
		{
			if(is_array($sPaymentMethods))
			{
				$sPaymentMethods = implode(',', $sPaymentMethods);
			}

			$sPaymentMethods = preg_replace('/[^A-Z0-9,]/', '', strtoupper($sPaymentMethods));
			$sPaymentMethods = str_replace('MEASTRO', 'MAESTRO', $sPaymentMethods); // Fix common typo
			$sPaymentMethods = str_replace('CREDITCARD', 'VISA,MASTERCARD,MAESTRO', $sPaymentMethods); // Fix default creditcards

			$this->aPaymentMethods = explode(',', $sPaymentMethods);
		}

		// Set amount in EURO, use a float or integer 
		public function setAmount($fOrderAmount, $sCurrencyCode = 'EUR')
		{
			$this->fOrderAmount = round($fOrderAmount, 2);
			$this->sCurrencyCode = $sCurrencyCode;
		}

		// Your secret hash key to secure form data (should match your Ideal Dashboard)
		public function setSecurityKey($sSecurityKey, $sSecurityKeyVersion = '1')
		{
			if($this->bTestMode === false) // Ignore settings when in TEST mode
			{
				$this->sSecurityKey = $sSecurityKey;
				$this->sSecurityKeyVersion = $sSecurityKeyVersion;
			}
		}

		// Your merchantID and subID
		public function setMerchant($sMerchantId, $sSubId = '0')
		{
			if($this->bTestMode === false) // Ignore settings when in TEST mode
			{
				$this->sMerchantId = $sMerchantId;
				$this->sSubId = $sSubId;
			}
		}

		// Upto 32 characters, should be a unique reference to your order
		public function setOrderId($sOrderId)
		{
			$this->sOrderId = substr(preg_replace('/[^a-zA-Z0-9]+/', '', $sOrderId), 0, 32);
		}

		// Upto 35 characters, should be a unique reference to your transaction
		public function setTransactionReference($sTransactionReference)
		{
			$this->sTransactionReference = substr(preg_replace('/[^a-zA-Z0-9]+/', '', $sTransactionReference), 0, 35);
		}

		// OmniKassa should support these languages: en, fr, de, it, es, nl
		public function setLanguageCode($sLanguageCode)
		{
			$this->sLanguageCode = strtolower(substr($sLanguageCode, 0, 2));
		}

		// Set test mode
		public function setTestMode()
		{
			$this->bTestMode = true;
			$this->sMerchantId = '002020000000001';
			$this->sSecurityKey = '002020000000001_KEY1';
			$this->sSecurityKeyVersion = '1';
			$this->sAquirerUrl = 'https://payment-webinit.simu.omnikassa.rabobank.nl/paymentServlet';
		}

		// Set Return URL
		public function setReturnUrl($sUrl)
		{
			if($iOffset = strpos($sUrl, '?'))
			{
				$sUrl = substr($sUrl, 0, $iOffset);
			}

			$this->sReturnUrl = $sUrl;
		}

		// Set Report URL
		public function setReportUrl($sUrl)
		{
			if($iOffset = strpos($sUrl, '?'))
			{
				$sUrl = substr($sUrl, 0, $iOffset);
			}

			$this->sReportUrl = $sUrl;
		}

		// Set submit button label, or define an image as submit-button
		public function setButton($sLabel, $sImage = false, $iWidth = 0, $iHeight = 0)
		{
			$this->sButtonLabel = $sLabel;
			$this->sButtonImage = $sImage;
			$this->iButtonImageWidth = $iWidth;
			$this->iButtonImageHeight = $iHeight;
		}

		// Generate payment form
		public function createForm()
		{
			$aData = array();

			$aData['merchantId'] = $this->sMerchantId;
			$aData['orderId'] = $this->sOrderId;
			$aData['amount'] = round($this->fOrderAmount * 100);
			$aData['customerLanguage'] = $this->sLanguageCode; // en, fr, de, it, es, nl
			$aData['keyVersion'] = $this->sSecurityKeyVersion;

			if(in_array('IDEAL', $this->aPaymentMethods) || in_array('MINITIX', $this->aPaymentMethods))
			{
				$aData['currencyCode'] = $this->getCurrencyNumber('EUR'); // Force EUR
			}
			else
			{
				$aData['currencyCode'] = $this->getCurrencyNumber($this->sCurrencyCode);
			}

			if(sizeof($this->aPaymentMethods))
			{
				$sPaymentMethods = implode(',', $this->aPaymentMethods);

				if(!empty($sPaymentMethods))
				{
					$aData['paymentMeanBrandList'] = $sPaymentMethods;
				}
			}

			$aData['transactionReference'] = ($this->sTransactionReference ? $this->sTransactionReference : md5(time() . $this->sOrderId));

			if($this->sReturnUrl)
			{
				$aData['normalReturnUrl'] = $this->sReturnUrl;
			}

			if($this->sReportUrl)
			{
				$aData['automaticResponseUrl'] = $this->sReportUrl;
			}

			$sData = '';

			foreach($aData as $k => $v)
			{
				// Remove pipeline
				$v = str_replace('|', '', $v);

				// Add to data string
				$sData .= (empty($sData) ? '' : '|') . ($k . '=' . $v);
			}

			$sHash = hash('sha256', utf8_encode($sData . $this->sSecurityKey));


			// Generate submit button
			$sSubmitButton = ($this->sButtonImage ? '<input type="image" value="' . htmlspecialchars($this->sButtonLabel) . '" src="' . htmlspecialchars($this->sButtonImage) . '"' . ($this->iButtonImageWidth ? ' width="' . htmlspecialchars($this->iButtonImageWidth) . '"' : '') . ($this->iButtonImageHeight ? ' height="' . htmlspecialchars($this->iButtonImageHeight) . '"' : '') . '>' : '<input type="submit" value="' . htmlspecialchars($this->sButtonLabel) . '">');

			// Generate HTML form
			$sHtml = '<form class="afrekenen_knop_right" method="post" action="' . htmlspecialchars($this->sAquirerUrl) . '" name="checkout"><input type="hidden" name="Data" value="' . htmlspecialchars($sData) . '"><input type="hidden" name="InterfaceVersion" value="HP_1.0"><input type="hidden" name="Seal" value="' . htmlspecialchars($sHash) . '">' . $sSubmitButton . '</form>';
			return $sHtml;
		}

		// Validate return/report 
		public function validate()
		{
			if(!empty($_POST['Data']) && !empty($_POST['Seal']))
			{
				$sData = $_POST['Data'];
				$sHash = $_POST['Seal'];

				// Valdate HASH
				if(strcmp($sHash, hash('sha256', utf8_encode($sData . $this->sSecurityKey))) === 0)
				{
					$a = explode('|', $sData);
					$aData = array();

					foreach($a as $d)
					{
						list($k, $v) = explode('=', $d);
						$aData[$k] = $v;
					}

					return array('transaction_reference' => $aData['transactionReference'], 'transaction_status' => $this->getTransactionStatus($aData['responseCode']), 'transaction_id' => (empty($aData['authorisationId']) ? '' : $aData['authorisationId']), 'order_id' => $aData['orderId'], 'raw_data' => $aData);
				}
			}

			return false;
		}

		// Translate currency code into currency number
		protected function getCurrencyNumber($sCurrencyCode)
		{
			// Extracted from http://www.currency-iso.org/dl_iso_table_a1.xml
			$aCurrencies = array('AED' => '784', 'AFN' => '971', 'ALL' => '008', 'AMD' => '051', 'ANG' => '532', 'AOA' => '973', 'ARS' => '032', 'AUD' => '036', 'AWG' => '533', 'AZN' => '944', 'BAM' => '977', 'BBD' => '052', 'BDT' => '050', 'BGN' => '975', 'BHD' => '048', 'BIF' => '108', 'BMD' => '060', 'BND' => '096', 'BOB' => '068', 'BOV' => '984', 'BRL' => '986', 'BSD' => '044', 'BTN' => '064', 'BWP' => '072', 'BYR' => '974', 'BZD' => '084', 'CAD' => '124', 'CDF' => '976', 'CHE' => '947', 'CHF' => '756', 'CHW' => '948', 'CLF' => '990', 'CLP' => '152', 'CNY' => '156', 'COP' => '170', 'COU' => '970', 'CRC' => '188', 'CUC' => '931', 'CUP' => '192', 'CVE' => '132', 'CZK' => '203', 'DJF' => '262', 'DKK' => '208', 'DOP' => '214', 'DZD' => '012', 'EGP' => '818', 'ERN' => '232', 'ETB' => '230', 'EUR' => '978', 'FJD' => '242', 'FKP' => '238', 'GBP' => '826', 'GEL' => '981', 'GHS' => '936', 'GIP' => '292', 'GMD' => '270', 'GNF' => '324', 'GTQ' => '320', 'GYD' => '328', 'HKD' => '344', 'HNL' => '340', 'HRK' => '191', 'HTG' => '332', 'HUF' => '348', 'IDR' => '360', 'ILS' => '376', 'INR' => '356', 'IQD' => '368', 'IRR' => '364', 'ISK' => '352', 'JMD' => '388', 'JOD' => '400', 'JPY' => '392', 'KES' => '404', 'KGS' => '417', 'KHR' => '116', 'KMF' => '174', 'KPW' => '408', 'KRW' => '410', 'KWD' => '414', 'KYD' => '136', 'KZT' => '398', 'LAK' => '418', 'LBP' => '422', 'LKR' => '144', 'LRD' => '430', 'LSL' => '426', 'LTL' => '440', 'LVL' => '428', 'LYD' => '434', 'MAD' => '504', 'MDL' => '498', 'MGA' => '969', 'MKD' => '807', 'MMK' => '104', 'MNT' => '496', 'MOP' => '446', 'MRO' => '478', 'MUR' => '480', 'MVR' => '462', 'MWK' => '454', 'MXN' => '484', 'MXV' => '979', 'MYR' => '458', 'MZN' => '943', 'NAD' => '516', 'NGN' => '566', 'NIO' => '558', 'NOK' => '578', 'NPR' => '524', 'NZD' => '554', 'OMR' => '512', 'PAB' => '590', 'PEN' => '604', 'PGK' => '598', 'PHP' => '608', 'PKR' => '586', 'PLN' => '985', 'PYG' => '600', 'QAR' => '634', 'RON' => '946', 'RSD' => '941', 'RUB' => '643', 'RWF' => '646', 'SAR' => '682', 'SBD' => '090', 'SCR' => '690', 'SDG' => '938', 'SEK' => '752', 'SGD' => '702', 'SHP' => '654', 'SLL' => '694', 'SOS' => '706', 'SRD' => '968', 'SSP' => '728', 'STD' => '678', 'SVC' => '222', 'SYP' => '760', 'SZL' => '748', 'THB' => '764', 'TJS' => '972', 'TMT' => '934', 'TND' => '788', 'TOP' => '776', 'TRY' => '949', 'TTD' => '780', 'TWD' => '901', 'TZS' => '834', 'UAH' => '980', 'UGX' => '800', 'USD' => '840', 'USN' => '997', 'USS' => '998', 'UYI' => '940', 'UYU' => '858', 'UZS' => '860', 'VEF' => '937', 'VND' => '704', 'VUV' => '548', 'WST' => '882', 'XAF' => '950', 'XAG' => '961', 'XAU' => '959', 'XBA' => '955', 'XBB' => '956', 'XBC' => '957', 'XBD' => '958', 'XCD' => '951', 'XDR' => '960', 'XFU' => 'Nil', 'XOF' => '952', 'XPD' => '964', 'XPF' => '953', 'XPT' => '962', 'XSU' => '994', 'XTS' => '963', 'XUA' => '965', 'XXX' => '999', 'YER' => '886', 'ZAR' => '710', 'ZMK' => '894', 'ZWL' => '932');


			// Find currency number
			if(isset($aCurrencies[$sCurrencyCode]))
			{
				return $aCurrencies[$sCurrencyCode];
			}


			// EURO
			return 978;
		}

		// Translate transaction status code
		protected function getTransactionStatus($sTransactionCode)
		{
			if(in_array($sTransactionCode, array('00'))) // SUCCESS
			{
				return 'SUCCESS';
			}
			elseif(in_array($sTransactionCode, array('60'))) // PENDING
			{
				return 'PENDING';
			}
			elseif(in_array($sTransactionCode, array('97'))) // EXPIRED
			{
				return 'EXPIRED';
			}
			elseif(in_array($sTransactionCode, array('17'))) // CANCELLED
			{
				return 'CANCELLED';
			}

			return 'FAILED';
		}
	}

?>