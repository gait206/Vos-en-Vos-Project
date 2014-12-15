<?php
session_start();
include('../functies.php');
$link = connectDB();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/afrekenen.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="../plaatjes/logo.png">
                </div>
                <div class="login">
                    <?php
                    include('../login/loginscherm.php');
                    ?>
                </div>
            </div>

            <!--<div class="menu">-->
                <?php
			define('THIS_PAGE', 'Afrekenen');
			include('../menu.php');
			?>

            <!--</div>-->

            <div class="content">

                <div class="body" id="main_content">

                    <?php
                    // User Storie 6

                    print('<table class="afrekenen_tabel">');



                    // table printen
                    print('<tr>'
                            . '<th>Product Naam</th>'
                            . '<th>Product Omschrijving</th>'
                            . '<th>Prijs</th>'
                            . '<th>BTW</th>'
                            . '<th>Aantal</th>'
                            . '<th>Totaal Bedrag</th></tr>');

                    // totaalBedragZonderBTW, totaalBedrag en totaalBTW instellen
                    $totaalBedragZonderBTW = 0;
                    $totaalBedrag = 0;
                    $totaalBTW = 0;
                    
                    // Test Waarden
                    if (existCookie("winkelmandje")) {
                        $cookie = getCookie("winkelmandje");

                        // rijen count voor opmaak
                        $count = 0;

                        // inhoud printen
                        foreach ($cookie as $key => $value) {
                            $result = mysqli_query($link, 'SELECT * FROM product WHERE productnr="' . $key . '";');

                            // eerste rij ophalen ** DATABASE **
                            $row = mysqli_fetch_assoc($result);

                            $product_naam = $row["productnaam"];
                            $product_omschrijving = $row["omschrijving"];
                            $product_prijs = $row["prijs"];
                            // ER IS NOG GEEN BTW tabel
                            $btw = 21;
                            $product_btw = 1 + ($btw / 100);

                            // berekening totaal prijs van aantal producten
                            $totalePrijsZonderBTW = 0;
                            $totalePrijsZonderBTW = $product_prijs * $value;
                            $totalePrijs = 0;
                            $totalePrijs = $totalePrijsZonderBTW * $product_btw;
                            // berekening totaal bedragen
                            $totaalBedragZonderBTW = $totaalBedragZonderBTW + $totalePrijsZonderBTW;
                            $totaalBedrag = $totaalBedrag + $totalePrijs;
                            $totaalBTW = $totaalBTW + ($totalePrijs - $totalePrijsZonderBTW);


                            // printen waarden
                            print('<tr>'
                                    . '<td>' . $product_naam . '</td>'
                                    . '<td>' . $product_omschrijving . '</td>'
                                    . '<td> &euro; ' . prijsformat($product_prijs) . '</td>'
                                    . '<td>' . $btw . '%</td>'
                                    . '<td>' . $value . '</td>'
                                    . '<td> &euro; ' . prijsformat($totalePrijsZonderBTW) . '</td></tr>');
                            $count++;
                        }
                    }
                    

                    // printen Totalen en balken
                    print('</table>');
                    print('<div class="afrekenen_totaal"><ul><li class="afrekenen_totaal_text"&euro;><h3>Bedrag Zonder BTW:</h3></li><li><h3> &euro; ' . prijsformat($totaalBedragZonderBTW) . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h3>Totaal BTW: </h3></li><li><h3>&euro; ' . prijsformat($totaalBTW) . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h2>Totaal: </h2></li><li><h2>&euro; ' . prijsformat($totaalBedrag) . '</h2></li></ul>');
                    ?>
                    <br>
                    <?php

	/*
		START EEN NIEUWE BETALING
	*/


	// Laad instellingen & bibliotheek
	require_once('/omnikassa/settings.php');
	require_once('/omnikassa/omnikassa.cls.5.php');




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
	$fOrderAmount = $totaalBedrag;

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
	$oOmniKassa->setReportUrl($aSettings['website_url'] . 'omnikassa/report.php'); // Mag geen additionele parameters bevatten
	$oOmniKassa->setReturnUrl($aSettings['website_url'] . '/verzenden.php'); // Mag geen additionele parameters bevatten

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
	$oOmniKassa->setButton('Betalen');





	// Zorg dat u de $sOrderId en de gegenereerde $sTransactionReference opslaat in bijv. de database zodat u bij de terugkoppeling de 
	// order kunt terug vinden en de status kunt bijwerken. Houdt er rekening mee dat bij ��n $sOrderId mogelijk meerdere betaalverzoeken 
	// worden ondernomen, waarbij steeds een nieuw (en uniek) $sTransactionReference moet worden opgegeven.
	// 
	// ... maatwerk ...
        
        // bezorgdatum tijdelijk toegevoegd als time() om te testen
        $klantnr = getKlantnr($link);
        // voegt de datum toe aan de database in YYYY-MM-DD formaat
        $besteldatum = date('Y-m-d', time());
        // tijdelijke waarde
        $bezorgdatum = date('Y-m-d', time());
        $status = "In Behandeling";
        $transactieref = $sTransactionReference;
        mysqli_query($link, 'INSERT INTO bestelling(besteldatum,bezorgdatum,status,klantnr,transactieref) VALUES("'.$besteldatum.'","'.$bezorgdatum.'","'.$status.'","'.$klantnr.'","'.$transactieref.'");');

        
        $result = mysqli_query($link, 'SELECT bestelnr FROM bestelling WHERE transactieref = "'.$transactieref.'";');
        $row = mysqli_fetch_assoc($result);
        $bestelnr = $row["bestelnr"];
        $cookie = getCookie("winkelmandje");
        
        foreach($cookie as $key => $value) {
           $stmt = mysqli_prepare($link, 'INSERT INTO bestelregel VALUES(?,?,?);');
           mysqli_stmt_bind_param($stmt, 'iii', $bestelnr, $key, $value);
           mysqli_execute($stmt);
           print(mysqli_stmt_error($stmt));
        }

        deleteCookie("winkelmandje");



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
	$sHtml = $oOmniKassa->createForm();

	// Voeg javascript toe om het formulier automatisch te verzenden
	if($aSettings['test_mode'] == false)
	{
		$sHtml .= '<script type="text/javascript"> function doAutoSubmit() { document.forms.checkout.submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
	}
print('<div class="afrekenen_knop_right">'.$sHtml.'</div>');
?>
                    </div>
                    <br>
                    <form method="POST" action="../winkelwagen.php"><input class="afrekenen_knop_left" type="submit" name="terug" value="Terug naar winkelwagen"></form>
                    
                </div>

            </div>

            <div class="footer">
            <?php
			include "../footer.php";
			?>
            </div>

        </div>
        <?php
        // put your code here
        ?>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script>
            $(function () {
                $("a.ajax-link").on("click", function (e) {
                    e.preventDefault();
                    $("#main_content").load(this.href);
                });
            });
        </script>
    </body>
</html>
<?php
            mysqli_close($link);
            ?>
