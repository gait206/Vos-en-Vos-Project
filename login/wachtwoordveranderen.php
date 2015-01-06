<?php
session_start();
include('../functies.php');
$link = connectDB();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/wachtwoordvergeten.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <a href="../index.php"><img class="logo" src="../plaatjes/logo.png"></a>
                </div>
                <div class="login">
                    <?php
                    include('../login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
            define('THIS_PAGE', 'wachtwoordveranderen');
            include('../menu.php');
            ?>

            <div class="content">
                <div class="body" id="main_content">
                    <div class="forgot_password">
                        <?php
                        if (!(empty($_GET["email"]) && empty($_GET["token"]))) {
                            $email = $_GET['email'];
                            $stmt3 = mysqli_prepare($link, 'SELECT klantnr FROM gebruiker WHERE email = ?;');
                            mysqli_stmt_bind_param($stmt3, 's', $email);
                            mysqli_stmt_execute($stmt3);
                            mysqli_stmt_bind_result($stmt3, $klantnr);
                            $result2 = mysqli_stmt_fetch($stmt3);
                            mysqli_stmt_close($stmt3);
                            
                            if (verifyPasswordForgot($klantnr, $_GET["token"], $link)) {
                                print('<h1>Wachtwoord Veranderen</h1>');
                                print('<p>Verander hier uw wachtwoord.</p>');

                                if (!empty($_POST["actie"])) {
                                    if (!empty($_POST["wachtwoord"]) && !$_POST["wachtwoord2"]) {
                                        if (!empty($_POST["wachtwoord"])) {
                                            $wachtwoord = $_POST["wachtwoord"];
                                        } else {
                                            print('<p class="foutmelding">Je moet het veld wachtwoord invullen!</p>');
                                        }
                                        if (!empty($_POST["wachtwoord2"])) {
                                            $wachtwoord2 = $_POST["wachtwoord2"];
                                        } else {
                                            print('<p class="foutmelding">Je moet het veld wachtwoord herhalen invullen!</p>');
                                        }
                                    } else {
                                        $wachtwoord = $_POST["wachtwoord"];
                                        $wachtwoord2 = $_POST["wachtwoord2"];
                                        $email = $_GET["email"];
                                        if ($wachtwoord = $wachtwoord2) {
                                            // dingen die moeten gebeuren als de wachtwoorden hetzelfde zijn
                                            $hash = encryptPassword($wachtwoord);
                                            $stmt2 = mysqli_prepare($link, 'UPDATE gebruiker SET wachtwoord = ? WHERE email = ?;');
                                            mysqli_stmt_bind_param($stmt2, 'ss', $hash, $email);
                                            mysqli_stmt_execute($stmt2);
                                            mysqli_stmt_close($stmt2);
                                            
                                            $stmt = mysqli_prepare($link, 'DELETE FROM recovery WHERE email = ?;');
                                            mysqli_stmt_bind_param($stmt, 's', $email);
                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_close($stmt);
                                            header('Location: ../index.php');
                                        } else {
                                            print('<p class="foutmelding">Je wachtwoorden komen niet overeen!</p>');
                                        }
                                    }
                                }
                                print('<form method="POST" action=""><input type="password" name="wachtwoord" placeholder="wachtwoord"><input type="password" name="wachtwoord2" placeholder="wachtwoord herhalen"><input type="submit" class="forgot_button" name="actie" value="Wachtwoord Veranderen"></form>');
                            } else {
                                print('<h1>Deze link is niet geldig</h1>');
                            }
                        } else {
                            print('<h1>Deze link is niet geldig</h1>');
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
