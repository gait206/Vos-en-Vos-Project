
<?php
if (validToken($link) != true) {
    // kijken of alle invoervelden ingevuld zijn
    if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
        $actie = $_POST["actie"];
        if ($actie == "Login") {
            // zorgt ervoor dat foutmeldingen worden weergeven
            if (!(empty($_POST["email"]) && empty($_POST["wachtwoord"]))) {
                if (!empty($_POST["email"])) {
                    $email = $_POST["email"];
                }
                
                if (!empty($_POST["wachtwoord"])) {
                    $password = $_POST["wachtwoord"];
                }
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
                        mysqli_query($link, 'DELETE FROM geblokkeerd WHERE klantnr = "'.$klantnr.'";');
                        header('Location: index.php');
                    }
                }
            }
        }
    }
}

if (validToken($link) == true) {
    // zorgt ervoor dat je kan uitloggen
    if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
        $actie = $_POST["actie"];
        if ($actie == "Uitloggen") {
            // verwijderd het token
            deleteToken("true", $link);
            header('Location: ../index.php');
            header('Location: index.php');
        }
    }
}
?>
                