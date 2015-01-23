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
                        // kijkt of de gebruiker niet ingelogd is
                        if (validToken($link)) {
                            header('Location: ../index.php');
                        } else {
                            print('<h1>Wachtwoord Vergeten</h1>');
                            print('<p>Als u al geregistreerd bent op onze website en u uw wachtwoord bent vergeten kan u die hier opvragen.</p>');

                            // kijkt of het formulier is opgestuurd
                            if (!empty($_POST["actie"])) {
                                if (!empty($_POST["email"])) {
                                    $email = $_POST["email"];

                                    // maakt een token aan
                                    $size = 60;
                                    $random = strtr(base64_encode(mcrypt_create_iv($size)), '+', '.');
                                    $salt = '$6$rounds=5000$';
                                    $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
                                    $token = crypt($random, $salt);

                                    // haalt het klantnr op uit de database
                                    $stmt3 = mysqli_prepare($link, 'SELECT klantnr FROM gebruiker WHERE email = ?;');
                                    mysqli_stmt_bind_param($stmt3, 's', $email);
                                    mysqli_stmt_execute($stmt3);
                                    mysqli_stmt_bind_result($stmt3, $klantnr);
                                    $result2 = mysqli_stmt_fetch($stmt3);
                                    mysqli_stmt_close($stmt3);

                                    // voegt een nieuwe regel toe aan de tabel recovery
                                    $time = time();
                                    $stmt = mysqli_prepare($link, 'INSERT recovery(klantnr,token,datum) VALUES(?, ?, ?)');
                                    mysqli_stmt_bind_param($stmt, 'isi', $klantnr, $token, $time);
                                    print(mysqli_stmt_error($stmt));
                                    mysqli_stmt_execute($stmt);
                                    print(mysqli_stmt_error($stmt));
                                    mysqli_stmt_close($stmt);

                                    // maakt 2 urls aan
                                    $url = '../login/wachtwoordveranderen.php?email=' . $email . '&token=' . $token . '';
                                    $url2 = '../login/wachtwoordnietveranderen.php?email=' . $email . '';

                                    
                                    
                                    // bericht van de mail
                                    $message = '<html><head></head><body>Als je een nieuw wachtwoord wil aanmaken <a href="' . $url . '">Klik dan hier</a><br>Als je geen nieuw wachtwoord wil aanmaken <a href="' . $url2 . '">Klik dan hier</a></body></html>';


                                    // stelt een tijdzone in
                                    date_default_timezone_set("UTC");
                                    // verstuurt de email
                                    mail($email, "wachtwoord vergeten", $message, "From:test@vos-vostissue.nl");


                                    print("Er is een email verstuurd naar uw account");
                                } else {
                                    print('<p class="foutmelding">Je moet een email invullen!</p>');
                                }
                            }

                            // weergeeft het formulier om een nieuw wachtwoord in te stellen
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
