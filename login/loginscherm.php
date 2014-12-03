
<?php
if (validToken($link) != true) {
    print('<form method="POST" action="">
                        <table>
                            <tr><td></td></tr>
                            <tr><td>Email:</td><td><input class="gebruikersnaam" type="text" name="email" placeholder="email"><br></td></tr>
                            <tr><td>Wachtwoord:</td><td><input class="wachtwoord" type="password" name="wachtwoord" placeholder="wachtwoord"></td></tr>
                            <tr><td><input type="submit" name="actie" value="Login"></td></tr>
                        </table>
                    </form>');

    // kijken of alle invoervelden ingevuld zijn
    if (isset($_POST["actie"]) && !empty($_POST["actie"])) {
        $actie = $_POST["actie"];
        if ($actie == "Login") {
            if (!(empty($_POST["email"]) && empty($_POST["wachtwoord"]))) {
                if (!empty($_POST["email"])) {
                    $email = $_POST["email"];
                } else {
                    print("Je bent je email vergeten");
                }
                if (!empty($_POST["wachtwoord"])) {
                    $password = $_POST["wachtwoord"];
                } else {
                    print("Je bent je wachtwoord vergeten");
                }
            } else {
                print("Je bent je email & wachtwoord vergeten");
            }
            if (!(empty($_POST["email"]) && empty($_POST["wachtwoord"])))
                if (verifyPassword($email, $password, $link)) {
                    if (!isset($_SESSION['initiated'])) {
                        session_regenerate_id();
                        $_SESSION['initiated'] = true;
                    }
                    createToken($email, $link);
                    header('Location: index.php');
                } else {
                    print("Wachtwoord Incorrect!");
                }
        }
    }
} else {
    print('<p>Welkom ' . getEmail($link) . '</p>');
}
?>
                