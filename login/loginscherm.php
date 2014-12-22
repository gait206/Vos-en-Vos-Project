
<?php

if (validToken($link) != true) {
    // kijken of alle invoervelden ingevuld zijn
    if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
        $actie = $_POST["actie"];
        if ($actie == "Login") {
            // kijkt of je gegevens bent vergeten en geeft aan wat je bent vergeten
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
            // word uitgevoerd als alle gegevens zijn ingevuld
            if (!empty($_POST["email"]) && !empty($_POST["wachtwoord"])) {
                // kijkt of het account is geblokkeerd of niet
                if (!accountBlocked($email, $link)) {
                    // kijkt of het wachtwoord correct is of niet
                    if (verifyPassword($email, $password, $link)) {
                        // regenereert het sessie id om de sessie veiliger te maken
                        if (!isset($_SESSION['initiated'])) {
                            session_regenerate_id();
                            $_SESSION['initiated'] = true;
                        }
                        // maakt een token aan en verwijderd de klant uit de tabel geblokkeerd
                        $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "' . $email . '";');
                        $row = mysqli_fetch_assoc($result);
                        $klantnr = $row["klantnr"];

                        createToken($klantnr, $link);
                        mysqli_query($link, 'DELETE FROM geblokkeerd WHERE klantnr = "'.$klantnr.'";');
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


    print('<form method="POST" action="">
                        <table>
                            <tr><td></td></tr>
                            <tr><td>Email:</td><td><input class="gebruikersnaam" type="text" name="email" placeholder="email"><br></td></tr>
                            <tr><td>Wachtwoord:</td><td><input class="wachtwoord" type="password" name="wachtwoord" placeholder="wachtwoord"></td></tr>
                            <tr><td><a class="wachtwoordvergeten_button" href="../login/wachtwoordvergeten.php">Wachtwoord vergeten?</a></td><td><a class="wachtwoordvergeten_button" href="../registratie/registreer.php">Registreren</a></td><td><input class="login_button" type="submit" name="actie" value="Login"></td></tr>
                        </table>
                    </form>');
} else {
    // word uitgevoerd als de gebruiker al ingelogd is
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
                