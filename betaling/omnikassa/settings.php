<?php

	$aSettings = array();

	// Standaard staan in dit configuratie bestand de instellingen voor de test-webwinkel. 
	// In de productie omgeving moet u gebruik maken van uw eigen gegevens die u vindt op de Downloadsite van de Rabo OmniKassa

	$aSettings['merchant_id'] = '002020000000001'; // Webwinkel ID - Deze vindt u op de Downloadsite van de Rabo OmniKassa
	$aSettings['security_key'] = '002020000000001_KEY1'; // Geheime sleutel - Deze vindt u op de Downloadsite van de Rabo OmniKassa
	$aSettings['security_key_version'] = '1'; // Geheime sleutel versie - Deze vindt u op de Downloadsite van de Rabo OmniKassa
	$aSettings['test_mode'] = true;

	$aSettings['website_url'] = _getWebsiteUrl(0);





	// Zoek de URL van het script
	function _getWebsiteUrl($iParentFolder = 0)
	{
		// Huidige domein
		$sUrl = ((isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0)) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];


		// Huidig pad
		$a = explode('/', $_SERVER['SCRIPT_NAME']);
		array_pop($a); // Verwijder /[bestandsnaam].php

		while($iParentFolder > 0)
		{
			array_pop($a); // Verwijder /map
			$iParentFolder--;
		}

		array_shift($a); // Verwijder eerste /

		$sUrl .= (sizeof($a) ? '/' . implode('/', $a) : '');


		// Resultaat: http://www.domain.nl/map (geen / op het eind)
		return $sUrl;
	}

?>