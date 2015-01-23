<?php
session_start();
include('../functies.php');
$link = connectDB();
$cookiename = 'winkelmandje';
if (!existCookie($cookiename)) {
    addCookie($cookiename, array());
}

if(validToken($link) == true) {
    // kijkt of er een actie moet worden uitgevoerd
                        if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
                            $actie = $_POST["actie"];
                            // kijkt of de gebruiker wil uitloggen
                            if ($actie == "Uitloggen") {
                                // verwijderd het token
                                deleteToken("true", $link);
                                header('Location: index.php');
                            }
                        }
}

if(validToken($link) != true) {
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
                                            header('Location: index.php');
                                        }
                                }
}
}

                        // kijkt of de cookie bestaat
                        if (existCookie('opmerking')) {
                            // haalt de cookie op als hij bestaat
                            $cookie = getCookie('opmerking');
                            // decrypt de cookie
                            $opmerking = decryptData($cookie['opmerking']);
                        } else {
                            $opmerking = '';
                        }
                        
                        if(isset($_POST['actie'])) {
                            $actie = $_POST['actie'];
                            
                            $opmerking = $_POST['opmerking'];
                            
                            $opmerking = encryptData($opmerking);
                            
                            $array = array();
                            $array['opmerking'] = $opmerking;
                            
                            addCookie('opmerking', $array);
                            header('Location: overzicht.php');
                        }
                    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/opmerking.css">
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
                                header('Location: http://localhost:8080/index.php');
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
                        // kijkt of de gebruik verder wil gaan
                        if (!empty($_POST['actie']) && $_POST['actie'] == 'Verder') {
                            // kijkt er een opmerking is ingevoerd
                            if (!empty($_POST['opmerking'])) {
                                $opmerking = $_POST['opmerking'];
                                $cookie = array();
                                // encrypt de opmerking
                                $cookie['opmerking'] = encryptData($opmerking);
                                // voegt een cookie toe
                                addCookie('opmerking', $cookie);
                            }
                            header('Location: overzicht.php');
                        }

                        // kijkt of de cookie bestaat
                        if (existCookie('opmerking')) {
                            // haalt de cookie op als hij bestaat
                            $cookie = getCookie('opmerking');
                            // decrypt de cookie
                            $opmerking = decryptData($cookie['opmerking']);
                        } else {
                            $opmerking = '';
                        }

                        // weergeeft het opmerkings formulier
                        print('<h1 class="kop">Opmerkingen</h1>');
                        print('<div class="opmerking"><p>Voeg hier uw opmerkingen toe:</p><form method="POST" action=""><textarea name="opmerking" rows="10" cols="100" maxlength="400">' . $opmerking . '</textarea><input class="verzenden_knop_right" type="submit" name="actie" value="Verder"></form></div>');
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