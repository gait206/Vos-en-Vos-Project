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
        <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <a href="../index.php"><img class="logo" src="../plaatjes/logo.png"></a>
                </div>
                <div class="login">
                    <?php
                    // word uitgevoerd als de gebruiker is ingelogd
                    if (validToken($link) == true) {
                        // kijkt of er een actie moet worden uitgevoerd
                        if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
                            $actie = $_POST["actie"];
                            // kijkt of de gebruiker wil uitloggen
                            if ($actie == "Uitloggen") {
                                // verwijderd het token
                                deleteToken("true", $link);
                                header('Location: ../index.php');
                            }
                        }
                        // geeft de welkoms boodschap weer
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
                                // zorgt ervoor dat foutmeldingen worden weergeven
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
                                    // kijkt of het account geblokkeerd is of niet
                                    if (!accountBlocked($email, $link)) {
                                        // kijkt of het wachtwoord klopt
                                        if (verifyPassword($email, $password, $link)) {
                                            // regenereert het sessie id voor extra veiligheid
                                            if (!isset($_SESSION['initiated'])) {
                                                session_regenerate_id();
                                                $_SESSION['initiated'] = true;
                                            }
                                            // haalt het klantnr van een gebruiker op uit de database
                                            $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "' . $email . '";');
                                            $row = mysqli_fetch_assoc($result);
                                            $klantnr = $row["klantnr"];

                                            // maakt een token aan
                                            createToken($klantnr, $link);
                                            mysqli_query($link, 'DELETE FROM geblokkeerd WHERE klantnr = "' . $klantnr . '";');
                                            header('Location: /');
                                        } else {
                                            print('<p class="foutmelding">Wachtwoord Incorrect!</p>');
                                            print(accountBlockedCount($email, $link));
                                        }
                                    } else {
                                        print('<p class="foutmelding">Dit account is geblokeerd kijk op uw email voor meer informatie</p>');
                                    }
                                }
                            }
                        }

                        // geeft het inlogscherm weer
                        print('<form method="POST" action="">
                        <table>
                            <tr><td></td></tr>
                            <tr><td>Email:</td><td><input class="gebruikersnaam" type="text" name="email" placeholder="email"><br></td></tr>
                            <tr><td>Wachtwoord:</td><td><input class="wachtwoord" type="password" name="wachtwoord" placeholder="wachtwoord"></td></tr>
                            <tr><td><a class="wachtwoordvergeten_button" href="../login/wachtwoordvergeten.php">Wachtwoord vergeten?</a></td><td><a class="wachtwoordvergeten_button" href="../registratie/registreer.php">Registreren</a></td><td><input class="login_button" type="submit" name="actie" value="Login"></td></tr>
                        </table>
                    </form>');
                    } else {
                        print('<div class="verzendadres"><h1 class="kop">Kies verzendadres</h1>');
                        
                        // vult de resultaten van het anderadres in als deze al waren ingevuld
                        if(existCookie('verzendadres')){
                            $cookie = getCookie('verzendadres');
                            
                            // decrypt alle data en haalt de padding weg
                            $plaats = rtrim(decryptData($cookie['plaats']));
                            $adres = rtrim(decryptData($cookie['adres']));
                            $postcode = rtrim(decryptData($cookie['postcode']));
                            
                        } else {
                        $plaats = "";
                        $adres = "";
                        $postcode = "";
                        }
                        
                        // kijkt of de gebruik verder wil gaan
                        if (!empty($_POST["actie"]) && $_POST["actie"] == "Verder") {
                            // kijkt of er een verzendadres is geselecteerd
                            if (!empty($_POST["verzendadres"])) {
                                // kijkt of het normale adres niet word gebruikt
                                if ($_POST["verzendadres"] != "adres") {
                                    if (!empty($_POST["plaats"]) && !empty($_POST["adres"]) && !empty($_POST["postcode"])) {
                                        // word uitgevoerd als er een ander adres word gekozen
                                        
                                        // encrypt de data zodat deze veilig kan worden opgeslagen
                                        $plaats = encryptData($_POST["plaats"]);
                                        $adres = encryptData($_POST["adres"]);
                                        $postcode = encryptData($_POST["postcode"]);
                                        
                                        $cookie = array();
                                        $cookie['plaats'] = $plaats;
                                        $cookie['adres'] = $adres;
                                        $cookie['postcode'] = $postcode;
                                        
                                        // maakt een cookie aan
                                        addCookie('verzendadres', $cookie);
                                        
                                        
                                        header('Location: opmerking.php');
                                    } else {
                                        print('<p class="foutmelding">Je hebt niet alle gegevens ingevuld!</p>');
                                    }
                                } else {
                                    // word uitgevoerd als het normale adres word gebruikt
                                    deleteCookie('verzendadres');
                                    header('Location: opmerking.php');
                                }
                            } else {
                                print('<p class="foutmelding">Kies een verzendadres!</p>');
                            }
                        }

                        // zorgt dat de keuze geselecteert blijft
                        if(existCookie('verzendadres')){
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }
                        
                        // weergeeft het normale adres
                        print('<form method="POST" action="" id="afleveradres"><div class="kolom1"><table><tr><td><input type="radio" name="verzendadres" value="adres" checked>Bedrijfs afleveradres</td></tr>');

                        // haalt het verzendadres uit de database
                        $result = mysqli_query($link, 'SELECT adres, plaats, postcode FROM klant WHERE klantnr ="' . getKlantnr($link) . '";');
                        $row = mysqli_fetch_assoc($result);

                        // weergeeft de data
                        print('<tr><td>Plaats: </td><td>' . $row["plaats"] . '</td></tr><tr><td>Adres: </td><td>' . $row["adres"] . '</td></tr><tr><td>Postcode: </td><td>' . $row["postcode"] . '</td></tr></table></div>');

                        // weergeeft het formulier met het alternatieve adres
                        print('<div class="kolom2" id="kolom2"><table><tr><td><input type="radio" name="verzendadres" value="anderadres" '.$checked.'>Ander afleveradres</td></tr>'
                                . '<tr><td>Plaats: </td><td><input type=text" name="plaats" placeholder="plaats" value="' . $plaats . '"></td></tr>'
                                . '<tr><td>Adres: </td><td><input type=text" name="adres" placeholder="adres" value="' . $adres . '"></td></tr>'
                                . '<tr><td>Postcode: </td><td><input type=text" name="postcode" placeholder="postcode" value="' . $postcode . '"></td></tr></table></div>');
                        print('<input class="verzenden_knop_right" type="submit" name="actie" value="Verder" form="afleveradres"></form>');
                    }
                    ?>

                    
                </div>
                <form method="POST" action="../winkelwagen.php"><input class="verzenden_knop_left" type="submit" name="terug" value="Terug naar winkelmandje"></form>
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