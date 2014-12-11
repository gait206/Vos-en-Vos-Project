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
        <link rel="stylesheet" type="text/css" href="../css/verzenden.css">
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
                        $email = getEmail($link);
                        $result = mysqli_query($link, 'SELECT voornaam, achternaam FROM klant WHERE email = "' . $email . '";');
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
                                        createToken($email, $link);
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
                        print('<div class="verzendadres"><h1 class="kop">Kies verzendadres</h1>'
                                . '<form method="POST" action=""><div class="kolom1"><input type="radio" name="verzendadres" value="adres">Bedrijfs afleveradres');
                        $result = mysqli_query($link, 'SELECT adres, plaats, postcode FROM klant WHERE email ="' . getEmail($link) . '";');
                        $row = mysqli_fetch_assoc($result);
                        print('<table><tr><td>Plaats: ' . $row["plaats"] . '</td></tr><tr><td>Adres: ' . $row["adres"] . '</td></tr><tr><td>Postcode: ' . $row["postcode"] . '</td></tr></table></div>');
                        print('<div class="kolom2"><input type="radio" name="verzendadres" value="anderadres" >Ander afleveradres</div>');
                        print('</form>');
                    }
                    ?>
                    <form method="POST" action="afrekenen.php"><input class="verzenden_knop_left" type="submit" name="terug" value="Terug naar controle"></form>
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