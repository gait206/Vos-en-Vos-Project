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
        <link rel="stylesheet" type="text/css" href="../css/wachtwoordvergeten.css">
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

            <?php
            define('THIS_PAGE', 'wachtwoordvergeten');
            include('../menu.php');
            ?>

            <div class="content">
                <div class="body" id="main_content">
                    <div class="forgot_password">
                        <?php
                        if (validToken($link)) {
                            header('Location: ../index.php');
                        } else {
                            print('<h1>Wachtwoord Vergeten</h1>');
                            print('<p>Als u al geregistreerd bent op onze website en u uw wachtwoord bent vergeten kan u die hier opvragen.</p>');

                            if (!empty($_POST["actie"])) {
                                if (!empty($_POST["email"])) {
                                    $email = $_POST["email"];
                                    // Vraag wachtwoord op 
                                    // gebruik dit bestand als referentie bestand
                                    // http://www.w3schools.com/php/php_ref_mail.asp

                                    $size = 60;
                                    $random = strtr(base64_encode(mcrypt_create_iv($size)), '+', '.');
                                    $salt = '$6$rounds=5000$';
                                    $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
                                    $token = crypt($random, $salt);
                                    
                                    mysqli_query($link, 'INSERT token(email,token,datum) VALUES("'.$email.'","'.$token.'","'.time().'")');
                                    
                                    $url = '../wachtwoordveranderen.php?email="'.$email.'"&token="'.$token.'"';
                                    $url2 = '../wachtwoordnietveranderen.php?email="'.$email.'"';
                                    // email opmaken en mail fixen
                                    
                                    $message = 'Als je een nieuw wachtwoord wil aanmaken klik dan op deze link: '.$url.'<br>Als je geen nieuw wachtwoord wil aanmaken klik dan op deze link: '.$url2;
                                    
                                    date_default_timezone_set("UTC");
                                    mail($email, "wachtwoord vergeten", $message, 'From: test@gait.tk');
                                    print("Er is een email verstuurd naar uw account");
                                } else {
                                    print('<p class="foutmelding">Je moet een email invullen!</p>');
                                }
                            }

                            print('<form method="POST" action=""><input type="text" name="email" placeholder="Email"><input type="submit" class="forgot_button" name="actie" value="Wachtwoord Opvragen"></form>');
                        }
                        ?>
                    </div>
                </div>


            </div>

            <div class="footer">

            </div>

        </div>

    </body>
</html>
<?php
mysqli_close($link);
?>
