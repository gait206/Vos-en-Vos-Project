<?php
session_start();
include('../functies.php');
$link = connectDB();
$cookiename = 'winkelmandje';
if (!existCookie($cookiename)) {
    addCookie($cookiename, array());
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/opmerking.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <a href="../index.php"><img class="logo" src="../plaatjes/logo.png"></a>
                </div>
                <div class="login">
                    <?php
                    if (validToken($link) == true) {
                        if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
                            $actie = $_POST["actie"];
                            if ($actie == "Uitloggen") {
                                deleteToken("true", $link);
                                header('Location: http://localhost:8080/index.php');
                            }
                        }
                        $klantnr = getKlantnr($link);
                        $result = mysqli_query($link, 'SELECT voornaam, achternaam FROM klant WHERE klantnr = "' . $klantnr . '" ');
                        $row = mysqli_fetch_assoc($result);
                        print('<p>Welkom, ' . $row["voornaam"] . ' ' . $row["achternaam"] . '</p>');
                        print('<div><form class="logout_button" method="POST" action=""><input type="submit" name="actie" value="Uitloggen"></form></div>');
                    }
                    ?>
                </div>
            </div>

            <?php
            define('THIS_PAGE', 'Verzenden');
            include('../menu.php');
            ?>

            <div class="content">
                <div class="body" id="main_content">
                    <?php
                    if (validToken($link) != true) {
                        // kijken of alle invoervelden ingevuld zijn
                        print('<div class="login_center">');
                        if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
                            $actie = $_POST["actie"];
                            if ($actie == "Login") {
                                if (!(empty($_POST["email"]) && empty($_POST["wachtwoord"]))) {
                                    if (!empty($_POST["email"])) {
                                        $email = $_POST["email"];
                                    } else {
                                        print('<p class="foutmelding">Je bent je email vergeten');
                                    }
                                    if (!empty($_POST["wachtwoord"])) {
                                        $password = $_POST["wachtwoord"];
                                    } else {
                                        print('<p class="foutmelding">Je bent je wachtwoord vergeten');
                                    }
                                } else {
                                    print('<p class="foutmelding">Je bent je email & wachtwoord vergeten');
                                }
                                if (!empty($_POST["email"]) && !empty($_POST["wachtwoord"])) {
                                    if (verifyPassword($email, $password, $link)) {
                                        if (!isset($_SESSION['initiated'])) {
                                            session_regenerate_id();
                                            $_SESSION['initiated'] = true;
                                        }
                                        $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "' . $email . '";');
                                        $row = mysqli_fetch_assoc($result);
                                        $klantnr = $row["klantnr"];
                                        createToken($klantnr, $link);
                                        header('Location: opmerking.php');
                                    } else {
                                        print('<p class="foutmelding">Wachtwoord Incorrect!</p>');
                                    }
                                }
                            }
                        }


                        print('<h1 class="kop"> Log in om te kunnen afrekenen</h1>
                            <form method="POST" action="" class="login_verzenden">
                        <table>
                            <tr><td></td></tr>
                            <tr><td>Email:</td><td><input class="gebruikersnaam" type="text" name="email" placeholder="email"><br></td></tr>
                            <tr><td>Wachtwoord:</td><td><input class="wachtwoord" type="password" name="wachtwoord" placeholder="wachtwoord"></td></tr>
                            <tr><td><a class="wachtwoordvergeten_button" href="../login/wachtwoordvergeten.php">Wachtwoord vergeten?</a></td><td><a class="wachtwoordvergeten_button" href="../registratie/registreer.php">Registreren</a></td><td><input class="login_button" type="submit" name="actie" value="Login"></td></tr>
                        </table>
                    </form>');
                        print('</div>');
                    } else {
                        // word uitgevoerd als de gebruiker is ingelogd
                        if (existCookie('verzendadres')) {
                            $verzendadres = getCookie('verzendadres');

                            $plaats = decryptData($verzendadres['plaats']);
                            $adres = decryptData($verzendadres['adres']);
                            $postcode = decryptData($verzendadres['postcode']);
                        } else {
                            $klantnr = getKlantnr($link);
                            $result = mysqli_query($link, 'SELECT plaats, adres, postcode FROM klant WHERE klantnr = "' . $klantnr . '";');
                            $row = mysqli_fetch_assoc($result);

                            $plaats = $row['plaats'];
                            $adres = $row['adres'];
                            $postcode = $row['postcode'];
                        }

                        if (existCookie('opmerking')) {
                            $cookie = getCookie('opmerking');
                            $opmerking = decryptData($cookie['opmerking']);
                        } else {
                            $opmerking = 'N.V.T.';
                        }

                        print('<div class="overzicht"><table><tr><td colspan=2><h1>Afleveradres</h1></td></tr>'
                                . '<tr><td>Plaats:</td><td>' . $plaats . '</td></tr>'
                                . '<tr><td>Adres:</td><td>' . $adres . '</td></tr>'
                                . '<tr><td>Postcode:</td><td>' . $postcode . '</td></tr>');

                        if (existCookie('opmerking')) {
                            print('<tr><td colspan=2><h1>Opmerkingen</h1></td></tr></table>');
                            print('<p>' . $opmerking . '</p>');
                        } else {
                            print('</table>');
                        }
                        print('</div>');

                        // totaalBedragZonderBTW, totaalBedrag en totaalBTW instellen
                        $totaalBedragZonderBTW = 0;
                        $totaalBedrag = 0;
                        $totaalBTW = 0;
                        $btw = 21;
                        $product_btw = 1 + ($btw / 100);

                        // kijken of het winkelmandje bestaat
                        if (existCookie("winkelmandje")) {
                            $cookie = getCookie("winkelmandje");

                            // inhoud printen
                            foreach ($cookie as $key => $value) {
                                $result = mysqli_query($link, 'SELECT prijs FROM product WHERE productnr="' . $key . '";');

                                // eerste rij ophalen uit de database
                                $row = mysqli_fetch_assoc($result);

                                $product_prijs = $row["prijs"];


                                // berekening totaal prijs van aantal producten
                                $totalePrijsZonderBTW = 0;
                                $totalePrijsZonderBTW = $product_prijs * $value;
                                $totalePrijs = 0;
                                $totalePrijs = $totalePrijsZonderBTW * $product_btw;
                                // berekening totaal bedragen
                                $totaalBedragZonderBTW = $totaalBedragZonderBTW + $totalePrijsZonderBTW;
                                $totaalBedrag = $totaalBedrag + $totalePrijs;
                                $totaalBTW = $totaalBTW + ($totalePrijs - $totalePrijsZonderBTW);
                            }
                        }

                        /*
                          START EEN NIEUWE BETALING
                         */


                        // Laad instellingen & bibliotheek
                        require_once('./omnikassa/settings.php');
                        require_once('./omnikassa/omnikassa.cls.5.php');



                        // Stel de tijdzone in (vaak vereist in PHP5 bij gebruik van datum/tijd functies)
                        if (function_exists('date_default_timezone_set')) {
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
                        //
                        // Deze setting moet aangepast worden om hem echt in gebruik te kunnen nemen
                        //
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
                        if (isset($aPaymentMethods) && is_array($aPaymentMethods) && sizeof($aPaymentMethods)) {
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
                        $stmt = mysqli_prepare($link, 'INSERT INTO bestelling(besteldatum,bezorgdatum,status,klantnr,opmerking,transactieref) VALUES(?,?,?,?,?,?);');
                        mysqli_stmt_bind_param($stmt, 'sssiss', $besteldatum, $bezorgdatum, $status, $klantnr, $opmerking, $sTransactionReference);
                        mysqli_execute($stmt);

                        $result = mysqli_query($link, 'SELECT bestelnr FROM bestelling WHERE transactieref = "' . $sTransactionReference . '";');
                        $row = mysqli_fetch_assoc($result);
                        $bestelnr = $row["bestelnr"];
                        $cookie = getCookie("winkelmandje");
                        
                        // word uitgevoerd als er een ander verzendadres word gebruikt
                        if (existCookie('verzendadres')) {
                            $cookie2 = getCookie('verzendadres');
                            $plaats2 = decryptData($cookie2['plaats']);
                            $adres2 = decryptData($cookie2['adres']);
                            $postcode2 = decryptData($cookie2['postcode']);

                            $stmt2 = mysqli_prepare($link, 'INSERT INTO anderadres(bestelnr,plaats,adres,postcode) VALUES(?,?,?,?);');
                            mysqli_stmt_bind_param($stmt2, 'isss', $bestelnr, $plaats2, $adres2, $postcode2);
                            mysqli_execute($stmt2);
                        }

                        foreach ($cookie as $key => $value) {
                            $stmt3 = mysqli_prepare($link, 'INSERT INTO bestelregel VALUES(?,?,?);');
                            mysqli_stmt_bind_param($stmt3, 'iii', $bestelnr, $key, $value);
                            mysqli_execute($stmt3);
                            print(mysqli_stmt_error($stmt3));
                        }

                        // Starten van de transactie opslaan in een log-bestand
                        $sLogData = 'TRANSACTION STARTED ON ' . date('d-m-Y, H:i:s') . "\r\n";
                        $sLogData .= 'TRANSACTION_REFERENCE: ' . $sTransactionReference . "\r\n";
                        $sLogData .= 'ORDER_ID: ' . $sOrderId . "\r\n\r\n\r\n";

                        $sLogPath = dirname(__FILE__) . '/logs';
                        $sLogFile = $sLogPath . '/' . $sTransactionReference . '.start.' . time() . '.log';

                        if (is_dir($sLogPath) && is_writable($sLogPath)) {
                            @file_put_contents($sLogFile, $sLogData);
                        }




                        // HTML code genereren
                        $sHtml = $oOmniKassa->createForm();

                        print('<div class="afrekenen_knop_right">' . $sHtml . '</div>');
                    }
                    ?>
                    <form method="POST" action="verzenden.php"><input class="verzenden_knop_left" type="submit" name="terug" value="Terug naar afleveradres"></form>
                </div>
            </div>

            <div class="footer">
<?php
include "../footer.php";
?>
            </div>
        </div>
    </body>
</html>
<?php
mysqli_close($link);
?>
