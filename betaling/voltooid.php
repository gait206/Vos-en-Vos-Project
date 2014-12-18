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
                    <img class="logo" src="../plaatjes/logo.png">
                </div>
                <div class="login">
                    <?php
                    if (validToken($link)) {
                        if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
                            $actie = $_POST["actie"];
                            if ($actie == "Uitloggen") {
                                deleteToken("true", $link);
                                header('Location: verzenden.php');
                            }
                        }
                        $klantnr = getKlantnr($link);
                        $result = mysqli_query($link, 'SELECT voornaam, achternaam FROM klant WHERE klantnr = "' . $klantnr . '";');
                        $row = mysqli_fetch_assoc($result);
                        print('<p>Welkom ' . $row["voornaam"] . ' ' . $row["achternaam"] . '</p>');
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
                                        header('Location: verzenden.php');
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
                        print('<h1>De bestelling is voltooid</h1><a href="/index.php" class="links">Klik hier om weer naar de index te gaan</a>');

                        include('factuur.php');
                        
                        //define the receiver of the email 
                        $klantnr = getKlantnr($link);
                        $result = mysqli_query($link, 'SELECT email FROM klant WHERE klantnr = "'.$klantnr.'";');
                        $row = mysqli_fetch_assoc($result);
                        $to = $row["email"];
//define the subject of the email 
                        $subject = 'Factuur bestelling';
//create a boundary string. It must be unique 
//so we use the MD5 algorithm to generate a random hash 
                        $random_hash = md5(date('r', time()));
//define the headers we want passed. Note that they are separated with \r\n 
                        $headers = "From: gertjan206@gmail.com\r\nReply-To: gertjan206@gmail.com";
//add boundary string and mime type specification 
                        $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-" . $random_hash . "\"";
//read the atachment file contents into a string,
//encode it with MIME base64,
//and split it into smaller chunks
                        $result = mysqli_query($link, 'SELECT bestelnr FROM bestelling WHERE transactieref = "'.$transactieref.'";');
                        $row = mysqli_fetch_assoc($result);
                        $bestelnr = $row["bestelnr"];
                        
                        $filename = 'bestelling_'.$bestelnr.'.pdf';
                        $attachment = chunk_split(base64_encode(file_get_contents($filename)));
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
                        Content-Type: application/zip; name="attachment.zip"  
                        Content-Transfer-Encoding: base64  
                        Content-Disposition: attachment  

                        <?php echo $attachment; ?> 
                        --PHP-mixed-<?php echo $random_hash; ?>-- 

                        <?php
//copy current buffer contents into $message variable and delete current output buffer 
                        $message = ob_get_clean();
//send the email 
                        $mail_sent = @mail($to, $subject, $message, $headers);
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
                        echo $mail_sent ? "Mail sent" : "Mail failed";
                    }
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