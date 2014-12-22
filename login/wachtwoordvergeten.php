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
                    <a href="../index.php"><img class="logo" src="../plaatjes/logo.png"></a>
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
                                    
                                    mysqli_query($link, 'INSERT recovery(email,token,datum) VALUES("'.$email.'","'.$token.'","'.time().'")');
                                    
                                    $url = 'http://localhost:8080/login/wachtwoordveranderen.php?email='.$email.'&token='.$token.'';
                                    $url2 = 'http://localhost:8080/login/wachtwoordnietveranderen.php?email='.$email.'';
                                    //
                                    //
                                    // email opmaken en mail fixen tijdelijke lokale server
                                    //
                                    //
                                    
                                    
                                    
                                    $message = '<html><head></head><body>Als je een nieuw wachtwoord wil aanmaken <a href="'.$url.'">Klik dan hier</a><br>Als je geen nieuw wachtwoord wil aanmaken <a href="'.$url2.'">Klik dan hier</a></body></html>';
                                    
             
//    }
            date_default_timezone_set("UTC");
            mail($email, "wachtwoord vergeten", $message, "From:gertjan206@gmail.com");
                                    

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
