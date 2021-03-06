<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (validToken($link) != true) {
    // kijken of alle invoervelden ingevuld zijn
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
                    if (!verifyPassword($email, $password, $link)) {
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
                            <tr><td><a class="wachtwoordvergeten_button" href="/login/wachtwoordvergeten.php">Wachtwoord vergeten?</a></td><td><a class="wachtwoordvergeten_button" href="../registratie/registreer.php">Registreren</a></td><td><input class="login_button" type="submit" name="actie" value="Login"></td></tr>
                        </table>
                    </form>');
}
    if (validToken($link) == true) {
    // geeft de welkoms boodschap weer
    $klantnr = getKlantnr($link);
    $result = mysqli_query($link, 'SELECT voornaam, achternaam FROM klant WHERE klantnr = "' . $klantnr . '" ');
    $row = mysqli_fetch_assoc($result);
    print('<p>Welkom, ' . $row["voornaam"] . ' ' . $row["achternaam"] . '</p>');
    print('<div><form class="logout_button" method="POST" action=""><input type="submit" name="actie" value="Uitloggen"></form></div>');
}