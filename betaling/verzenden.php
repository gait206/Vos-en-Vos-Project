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
                    <a href="../index.php"><img class="logo" src="../plaatjes/logo.png"></a>
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
                        $klantnr = getKlantnr($link);
                        $result = mysqli_query($link, 'SELECT voornaam, achternaam FROM klant WHERE klantnr = "' . $klantnr . '";');
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
                                        $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "'.$email.'";');
					$row = mysqli_fetch_assoc($result);
					$klantnr = $row["klantnr"];
                                        createToken($klantnr, $link);
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
                        print('<div class="verzendadres"><h1 class="kop">Kies verzendadres</h1>');
                        $plaats = "";
                        $adres = "";
                        $postcode = "";
                        if(!empty($_POST["actie"]) && $_POST["actie"] == "Afronden"){
                        if (!empty($_POST["verzendadres"])) {
                            if ($_POST["verzendadres"] != "adres") {
                                if (!empty($_POST["plaats"]) && !empty($_POST["adres"]) && !empty($_POST["postcode"])) {
                                    // word uitgevoerd als het normale adres niet word gebruikt
                                    $plaats = $_POST["plaats"];
                                    $adres = $_POST["adres"];
                                    $postcode = $_POST["postcode"];
                                    
                                    $stmt = mysqli_prepare($link, 'INSERT INTO anderadres VALUES(?,?,"'.$adres.'","'.$postcode.'");');
                                    mysqli_stmt_bind_param($stmt, 'is', $bestelnr, $plaats);
                                    mysqli_stmt_execute($stmt);
                                    
                                } else {
                                    print('<p class="foutmelding">Je hebt niet alle gegevens ingevuld!</p>');
                                }
                            } else {
                                // word uitgevoerd als het normale adres word gebruikt
                            }
                        } else {
                            print('<p class="foutmelding">Kies een verzendadres!</p>');
                        }
                        }


                        // kolom1 adres
                        
                        print('<form method="POST" action="" id="afleveradres"><div class="kolom1"><table><tr><td><input type="radio" name="verzendadres" value="adres">Bedrijfs afleveradres</td></tr>');

                        $result = mysqli_query($link, 'SELECT adres, plaats, postcode FROM klant WHERE klantnr ="' . getKlantnr($link) . '";');
                        $row = mysqli_fetch_assoc($result);

                        print('<tr><td>Plaats: </td><td>' . $row["plaats"] . '</td></tr><tr><td>Adres: </td><td>' . $row["adres"] . '</td></tr><tr><td>Postcode: </td><td>' . $row["postcode"] . '</td></tr></table></div>');

                        // kolom2 ander adres
                        print('<div class="kolom2" id="kolom2"><table><tr><td><input type="radio" name="verzendadres" value="anderadres">Ander afleveradres</td></tr>'
                                . '<tr><td>Plaats: </td><td><input type=text" name="plaats" placeholder="plaats" value="' . $plaats . '"></td></tr>'
                                . '<tr><td>Adres: </td><td><input type=text" name="adres" placeholder="adres" value="' . $adres . '"></td></tr>'
                                . '<tr><td>Postcode: </td><td><input type=text" name="postcode" placeholder="postcode" value="' . $postcode . '"></td></tr></table></div>');
                        print('</form>');
                    }
                    ?>
<input class="verzenden_knop_right" type="submit" name="actie" value="Afronden" form="afleveradres">
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