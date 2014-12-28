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
                    if (isset($_POST["actie"]) &&!empty($_POST["actie"])) {
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
                        if(!empty($_POST['actie']) && $_POST['actie'] == 'Verder'){
                            if(!empty($_POST['opmerking'])){
                            $opmerking = $_POST['opmerking'];
                            $cookie = array();
                            $cookie['opmerking'] = encryptData($opmerking);
                            addCookie('opmerking', $cookie);
                            }
                            header('Location: overzicht.php');
                        }
                        
                        if(existCookie('opmerking')){
                            $cookie = getCookie('opmerking');
                            
                            $opmerking = decryptData($cookie['opmerking']);
                        } else {
                            $opmerking = '';
                        }
                        
                        print('<h1 class="kop">Opmerkingen</h1>');
                        print('<div class="opmerking"><p>Voeg hier uw opmerkingen toe:</p><form method="POST" action=""><textarea name="opmerking" rows="10" cols="100">'.$opmerking.'</textarea><input class="verzenden_knop_right" type="submit" name="actie" value="Verder"></form></div>');
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