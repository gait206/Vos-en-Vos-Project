<?php

	/*
		Verwerk de status rapportage/update van de Rabo OmniKassa
		Report URL / automaticResponseUrl
	*/


	// Laad instellingen & bibliotheek
	require_once('settings.php');
	require_once('omnikassa.cls.5.php');





	// Controleer of de benodigde POST-data is ontvangen
	if(empty($_POST['Data']) || empty($_POST['Seal']))
	{
		$sHtml = '<p>Ongeldige Omnikassa Response.</p>';
	}
	else
	{
		$oOmniKassa = new OmniKassa();
		$oOmniKassa->setSecurityKey($aSettings['security_key'], $aSettings['security_key_version']);

		$aOmniKassaResponse = $oOmniKassa->validate();

		if($aOmniKassaResponse && is_array($aOmniKassaResponse))
		{
			// De referentiecode die bij het starten van het betaalverzoek is opgegeven, belangrijk om in de 
			// database de bijbehorende bestelling op te zoeken.
			$sTransactionReference = $aOmniKassaResponse['transaction_reference']; 

			// De huidige status van de betaalverzoek. De ontvangen responseCode wordt door de bibliotheek
			// omgezet in de waarde SUCCESS, PENDING, CANCELLED, EXPIRED of FAILED.
			$sTransactionStatus = $aOmniKassaResponse['transaction_status']; 

			// Bij sommige betaalmethoden (zoals iDEAL) wordt deze waarde gevuld met het "authorisationId", 
			// dit is de door de iDEAL server toegewezen unieke TransactionID.
			$sTransactionId = $aOmniKassaResponse['transaction_id']; 

			// Het orderID (Alleen de karakters [a-zA-Z0-9] en gelimiteerd tot 32 karakters). 
			// Door de mutaties die soms plaats vinden in dit orderID is dit doorgaans GEEN goede 
			// waarde om de bestelling op te zoeken in de database.
			$sOrderId = $aOmniKassaResponse['order_id']; 




			// Houd er rekening mee dat de status mogelijk al is verwerkt door de "Return URL"
			// om te voorkomen dat het systeem denkt dat een order 2x is betaald.
			// 
			// Controleer daarom altijd of de status is veranderd t.o.v. de laatste status.
			// 
			// ... maatwerk ...





			// Verwerking van de automaticResponseUrl opslaan in een log-bestand
			$sLogData = 'REPORT RECIEVED ON ' . date('d-m-Y, H:i:s') . "\r\n";
			$sLogData .= 'TRANSACTION_REFERENCE: ' . $sTransactionReference . "\r\n";
			$sLogData .= 'TRANSACTION_STATUS: ' . $sTransactionStatus . "\r\n";
			$sLogData .= 'TRANSACTION_ID: ' . $sTransactionId . "\r\n";
			$sLogData .= 'ORDER_ID: ' . $sOrderId . "\r\n\r\n\r\n";

			$sLogPath = dirname(__FILE__) . '/logs';
			$sLogFile = $sLogPath . '/' . $sTransactionReference . '.report.' . time() . '.log';

			if(is_dir($sLogPath) && is_writable($sLogPath))
			{
				@file_put_contents($sLogFile, $sLogData);
			}





			// Bepaal de transactie status, en bevestig deze aan de bezoeker
			if(strcmp($sTransactionStatus, 'SUCCESS') === 0)
			{
				$sHtml = '<p>Uw betaling is met succes ontvangen.<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/start.php') . '">Nieuwe transactie starten.</a></p>';
			}
			elseif(strcmp($sTransactionStatus, 'PENDING') === 0)
			{
				$sHtml = '<p>Uw betaling is in behandeling.<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/start.php') . '">Nieuwe transactie starten.</a></p>';
			}
			elseif(strcmp($sTransactionStatus, 'CANCELLED') === 0)
			{
				$sHtml = '<p>Uw betaling is geannuleerd.<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/start.php') . '">Probeer opnieuw te betalen.</a></p>';
			}
			elseif(strcmp($sTransactionStatus, 'EXPIRED') === 0)
			{
				$sHtml = '<p>Uw betaling is mislukt.<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/start.php') . '">Probeer opnieuw te betalen.</a></p>';
			}
			else // if(strcmp($sTransactionStatus, 'FAILURE') === 0)
			{
				$sHtml = '<p>Uw betaling is mislukt.<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/start.php') . '">Probeer opnieuw te betalen.</a></p>';
			}
		}
		else
		{
			$sHtml = '<p>Ongeldige Omnikassa Response (verkeerde beveiligingssleutel ingesteld?).<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/start.php') . '">Nieuwe transactie starten.</a></p>';
		}
	}

	// Merk op dat dit script via een 'cronjob' door de webserver van de Rabobank wordt opgeroepen.
	// Er kan niet geredirect worden, en code die u print wordt niet verwerkt door de de webserver.
	// echo $sHtml;
	echo 'RAPPORTAGE VERWERKT';

?>