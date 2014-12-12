<?php

	/*
		START EEN NIEUWE BETALING
	*/


	// Laad instellingen & bibliotheek
	require_once('settings.php');
	require_once('omnikassa.cls.5.php');




	// Stel de tijdzone in (vaak vereist in PHP5 bij gebruik van datum/tijd functies)
	if(function_exists('date_default_timezone_set'))
	{
		date_default_timezone_set('Europe/Amsterdam');
	}





	// Bepaal het ordernummer (wordt vermeld in het Dashboard van de Rabo OmniKassa en bij de afschrijving van de klant).
	// Normaal wordt dit nummer uit het systeem gehaald, in dit voorbeeld wordt een random nummer gebruikt
	$sOrderId = 'WEB-' . time();

	// Bepaal het totaalbedrag dat de klant moet betalen in de Rabo OmniKassa
	// Normaal wordt dit bedrag uit het systeem gehaald, in dit voorbeeld wordt een random bedrag gebruikt tussen de 1.00 en de 250.00 EURO
	$fOrderAmount = round(rand(100, 25000) / 100, 2);

	// Als u geen betaalmethode opgeeft, dan ziet de klant in de Rabo OmniKassa een overzicht met alle beschikbare betaalmethoden waarvan hij er 1 kan kiezen.
	// Als u 1 betaalmethode instelt, wordt het scherm met de betaalkeuze overgeslagen, en start de betaling gelijk.
	// Als u meerdere betaalmethoden instelt, dan ziet de klant in de Rabo OmniKassa alleen de opgegeven betaalmethoden waarvan hij er 1 kan kiezen.
	// Note: u kunt alleen betaalmethoden aanbieden die u bij de Rabobank hebt aangevraagd.
	// Beschikbare betaalmethoden: IDEAL, MINITIX, VISA, MASTERCARD, MAESTRO, INCASSO, ACCEPTGIRO, REMBOURS.
	$aPaymentMethods = array('IDEAL'); // Laat de klant zelf een betaalmethode kiezen op de betaalpagina van de Rabo OmniKassa.
	// $aPaymentMethods = array('IDEAL'); // Alleen iDEAL toestaan (keuzescherm wordt overgeslagen)
	// $aPaymentMethods = array('IDEAL', 'MINITIX', 'VISA', 'MASTERCARD', 'MAESTRO'); // iDEAL, MiniTix, Visa, Mastercard of Maestro toestaan.



	$oOmniKassa = new OmniKassa($aSettings['test_mode']);
	$oOmniKassa->setMerchant($aSettings['merchant_id']);
	$oOmniKassa->setSecurityKey($aSettings['security_key'], $aSettings['security_key_version']);

	// Stel de return URL en report URL in (normalReturnUrl, automaticResponseUrl)
	$oOmniKassa->setReportUrl($aSettings['website_url'] . '/report.php'); // Mag geen additionele parameters bevatten
	$oOmniKassa->setReturnUrl($aSettings['website_url'] . '/return.php'); // Mag geen additionele parameters bevatten

	// Stel order informatie in
	$oOmniKassa->setOrderId($sOrderId); // Unieke order referentie, tot 32 karakters ([a-zA-Z0-9]+)
	$oOmniKassa->setAmount($fOrderAmount); // Bedrag (in EURO's, tot 2 decimalen, gebruik een punt als scheidingsteken)


	// Stel de beschikbare betaalmethode(n) in voor de koper (indien ingesteld).
	if(isset($aPaymentMethods) && is_array($aPaymentMethods) && sizeof($aPaymentMethods))
	{
		$oOmniKassa->setPaymentMethod($aPaymentMethods); 
	}

	// Houd er rekening mee dat voor ELK BETAALVERZOEK een unieke referentie opgegeven moet worden.
	// In dit voorbeeld gebruiken we de tijd om de code uniek te maken, maar beter zou een pogingnummer o.i.d. zijn.
	$sTransactionReference = $sOrderId . 'x' . date('His'); // Unieke transactie referentie, tot 35 karakters ([a-zA-Z0-9]+)
	$oOmniKassa->setTransactionReference($sTransactionReference);

	// Customize submit button
	$oOmniKassa->setButton('Verder >>');





	// Zorg dat u de $sOrderId en de gegenereerde $sTransactionReference opslaat in bijv. de database zodat u bij de terugkoppeling de 
	// order kunt terug vinden en de status kunt bijwerken. Houdt er rekening mee dat bij ��n $sOrderId mogelijk meerdere betaalverzoeken 
	// worden ondernomen, waarbij steeds een nieuw (en uniek) $sTransactionReference moet worden opgegeven.
	// 
	// ... maatwerk ...





	// Starten van de transactie opslaan in een log-bestand
	$sLogData = 'TRANSACTION STARTED ON ' . date('d-m-Y, H:i:s') . "\r\n";
	$sLogData .= 'TRANSACTION_REFERENCE: ' . $sTransactionReference . "\r\n";
	$sLogData .= 'ORDER_ID: ' . $sOrderId . "\r\n\r\n\r\n";

	$sLogPath = dirname(__FILE__) . '/logs';
	$sLogFile = $sLogPath . '/' . $sTransactionReference . '.start.' . time() . '.log';

	if(is_dir($sLogPath) && is_writable($sLogPath))
	{
		@file_put_contents($sLogFile, $sLogData);
	}




	// HTML code genereren
	$sHtml = '<p><b>Direct online afrekenen via de Rabo OmniKassa<br><br>ORDER: ' . $sOrderId . '<br>BEDRAG: ' . number_format($fOrderAmount, 2, ',', '') . ' EUR.</b></p>' . $oOmniKassa->createForm();

	// Voeg javascript toe om het formulier automatisch te verzenden
	if($aSettings['test_mode'] == false)
	{
		$sHtml .= '<script type="text/javascript"> function doAutoSubmit() { document.forms.checkout.submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
	}

	echo $sHtml;

?>