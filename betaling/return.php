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
        <link rel="stylesheet" type="text/css" href="../css/return.css">
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
                    include('../login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
            define('THIS_PAGE', 'Return');
            include('../menu.php');
            ?>

            <div class="content">
                <div class="body" id="main_content">
                    <?php
                    /*
                      Verwerk de status rapportage/update van de Rabo OmniKassa
                      Return URL / normalReturnUrl
                     */


                    // Laad instellingen & bibliotheek
                    require_once('/omnikassa/settings.php');
                    require_once('/omnikassa/omnikassa.cls.5.php');



                    // Stel de tijdzone in (vaak vereist in PHP5 bij gebruik van datum/tijd functies)
                    if (function_exists('date_default_timezone_set')) {
                        date_default_timezone_set('Europe/Amsterdam');
                    }

                    // Controleer of de benodigde POST-data is ontvangen
                    if (empty($_POST['Data']) || empty($_POST['Seal'])) {
                        $sHtml = '<h1 class="error">Ongeldige Omnikassa Response.</h1><br><a href="../index.php" class="links">Klik hier om terug te gaan naar de index</a>';
                    } else {
                        $oOmniKassa = new OmniKassa();
                        $oOmniKassa->setSecurityKey($aSettings['security_key'], $aSettings['security_key_version']);

                        $aOmniKassaResponse = $oOmniKassa->validate();

                        if ($aOmniKassaResponse && is_array($aOmniKassaResponse)) {
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





                            // Zoek de order op d.m.v. de unieke $sTransactionReference
                            // Houd er rekening mee dat de status mogelijk al is verwerkt door de "Report URL"
                            // om te voorkomen dat het systeem denkt dat een order 2x is betaald.
                            // 
                            // Controleer daarom altijd of de status is veranderd t.o.v. de laatste status.
                            // 
                            // ... maatwerk ...
                            // Verwerking van de normalReturnUrl opslaan in een log-bestand
                            $sLogData = 'RETURN RECIEVED ON ' . date('d-m-Y, H:i:s') . "\r\n";
                            $sLogData .= 'TRANSACTION_REFERENCE: ' . $sTransactionReference . "\r\n";
                            $sLogData .= 'TRANSACTION_STATUS: ' . $sTransactionStatus . "\r\n";
                            $sLogData .= 'TRANSACTION_ID: ' . $sTransactionId . "\r\n";
                            $sLogData .= 'ORDER_ID: ' . $sOrderId . "\r\n\r\n\r\n";

                            $sLogPath = dirname(__FILE__) . '/logs';
                            $sLogFile = $sLogPath . '/' . $sTransactionReference . '.return.' . time() . '.log';

                            if (is_dir($sLogPath) && is_writable($sLogPath)) {
                                file_put_contents($sLogFile, $sLogData);
                            }
                            




                            // Bepaal de transactie status, en bevestig deze aan de bezoeker
                            if (strcmp($sTransactionStatus, 'SUCCESS') === 0) {
                                $sHtml = '<h1 class="error">Uw betaling is met succes ontvangen.<br>U word over 5 seconden doorgestuurd of<br><a href="/index.php">Klik hier om verder te gaan</a></h1>';
                                // moet een mail naar de klant sturen
                                // formaat van sTransactionReference aanpassen zodat deze overeen komt met de waarde in de database
                                $sTransactionReference = substr($sTransactionReference, 0, 3).'-'.substr($sTransactionReference, 3,strlen($sTransactionReference));
                                mysqli_query($link, 'UPDATE bestelling SET betaald = "ja" WHERE transactieref = "'.$sTransactionReference.'"');
                                
                                // verwijderen van alle cookies
                                deleteCookie('winkelmandje');
                                deleteCookie('verzendadres');
                                deleteCookie('opmerking');
                                
                                // aanmaken factuur
                                
                                
                                // verzenden factuur via email
                                //define the receiver of the email 
                        $klantnr = getKlantnr($link);
                        $result = mysqli_query($link, 'SELECT email FROM gebruiker WHERE klantnr = "' . $klantnr . '";');
                        $row = mysqli_fetch_assoc($result);
                        $to = $row["email"];
                        //define the subject of the email 
                        $subject = 'Factuur bestelling';
                        //create a boundary string. It must be unique 
                        //so we use the MD5 algorithm to generate a random hash 
                        $random_hash = md5(date('r', time()));
                        //define the headers we want passed. Note that they are separated with \r\n 
                        $headers = 'From: gertjan206@gmail.com' . "\r\n";
                        $headers .= 'Reply-To: gertjan206@gmail.com' . "\r\n";
                        $headers .= 'X-Mailer: PHP/' . phpversion(). "\r\n";
                        $headers .= 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-Type: multipart/mixed; boundary="PHP-mixed-'.$random_hash.'"' . "\r\n";
                        
                        //add boundary string and mime type specification 
                        //read the atachment file contents into a string,
                        //encode it with MIME base64,
                        //and split it into smaller chunks
                        $result = mysqli_query($link, 'SELECT bestelnr FROM bestelling WHERE transactieref = "' . $sTransactionReference . '";');
                        $row = mysqli_fetch_assoc($result);
                        $bestelnr = $row["bestelnr"];

                        $filename = 'facturen/bestelling_' . $bestelnr . '.pdf';
                        
                        createFactuur('bestelling_' . $bestelnr . '.pdf', $bestelnr);
                        $attachment = chunk_split(base64_encode(file_get_contents($filename)));
                        
                        $filename = substr($filename, 9, strlen($filename));
                        //define the body of the message. 
                        ob_start(); //Turn on output buffering 
                        ?> 
                        
                        
                        --PHP-mixed-<?php echo $random_hash; ?>  
                        Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>" 

                        --PHP-alt-<?php echo $random_hash; ?>  
                        Content-Type: text/plain; charset="iso-8859-1" 
                        Content-Transfer-Encoding: 7bit

                        Hello World!!! 
                        This is simple text email message. 

                        --PHP-alt-<?php echo $random_hash; ?>  
                        Content-Type: text/html; charset="iso-8859-1" 
                        Content-Transfer-Encoding: 7bit

                        <h2>Hello World!</h2> 
                        <p>This is something with <b>HTML</b> formatting.</p> 

                        --PHP-alt-<?php echo $random_hash; ?>-- 

                        --PHP-mixed-<?php echo $random_hash; ?>  
                        Content-Type: application/pdf; name="<?php echo $filename ?>"
                        Content-Transfer-Encoding: base64
                        Content-Disposition: attachment; filename="<?php echo $filename ?>"

                        <?php echo $attachment; ?> 
                        --PHP-mixed-<?php echo $random_hash; ?>-- 

                        <?php
                        //copy current buffer contents into $message variable and delete current output buffer 
                        $message = ob_get_clean();
                        //send the email 
                        $mail_sent = mail($to, $subject, $message, $headers);
                        //if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
                        
                                //print('<script>setTimeout( function(){window.location.href= "/index.php";},5000);</script>');
                            } elseif (strcmp($sTransactionStatus, 'PENDING') === 0) {
                                $sHtml = '<h1 class="error">Uw betaling is in behandeling.<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/winkelmandje.php') . '" class="links">Nieuwe transactie starten.</a></h1>';
                            } elseif (strcmp($sTransactionStatus, 'CANCELLED') === 0) {
                                $sHtml = '<h1 class="error">Uw betaling is geannuleerd.<br><a href="' . htmlspecialchars($aSettings['website_url'] . 'overzicht.php') . '" class="links">Probeer opnieuw te betalen.</a></h1>';
                            } elseif (strcmp($sTransactionStatus, 'EXPIRED') === 0) {
                                $sHtml = '<h1 class="error">Uw betaling is mislukt.<br><a href="' . htmlspecialchars($aSettings['website_url'] . 'overzicht.php') . '" class="links">Probeer opnieuw te betalen.</a></h1>';
                            } else { // if(strcmp($sTransactionStatus, 'FAILURE') === 0)
                                $sHtml = '<h1 class="error">Uw betaling is mislukt.<br><a href="' . htmlspecialchars($aSettings['website_url'] . 'overzicht.php') . '" class="links">Probeer opnieuw te betalen.</a></h1>';
                            }
                        } else {
                            $sHtml = '<h1 class="error">Ongeldige Omnikassa Response (verkeerde beveiligingssleutel ingesteld?).<br><a href="' . htmlspecialchars($aSettings['website_url'] . '/winkelmandje.php') . '" class="links">Nieuwe transactie starten.</a></h1>';
                        }
                    }

                    print($sHtml);
                    ?>
                    
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