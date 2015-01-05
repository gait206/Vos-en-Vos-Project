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

                </div>
            </div>

            <?php
            define('THIS_PAGE', 'Inloggen');
            include('../menu.php');
            ?>

            <div class="content">
                <div class="body" id="main_content">
                    <div class="login_center">
                        <?php
                        if (validToken($link) != true) {
                            // kijken of alle invoervelden ingevuld zijn
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
                                        if (!accountBlocked($email, $link)) {
                                            if (verifyPassword($email, $password, $link)) {
                                                if (!isset($_SESSION['initiated'])) {
                                                    session_regenerate_id();
                                                    $_SESSION['initiated'] = true;
                                                }
                                                $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "' . $email . '";');
                                                $row = mysqli_fetch_assoc($result);
                                                $klantnr = $row["klantnr"];

                                                createToken($klantnr, $link);
                                                mysqli_query($link, 'DELETE FROM geblokkeerd WHERE klantnr = "' . $klantnr . '";');
                                                header('Location: verzenden.php');
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
                            header('Location: http://localhost:8080/betaling/verzenden.php');
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