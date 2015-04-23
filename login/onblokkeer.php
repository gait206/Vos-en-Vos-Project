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
                        // kijkt of alle variabelen meegestuurd zijn
                        if(!(empty($_GET["email"]) && empty($_GET["token"]))){
                            $klantnr = $_GET["klantnr"];
                            $token = $_GET["token"];
                            // verwijderd de gebruiker uit de tabel geblokkeerd
                            mysqli_query($link, 'DELETE FROM geblokkeerd WHERE klantnr = "'.$klantnr.'" AND token = "'.$token.'";');
                            
                            print('<h1>Uw account is weer beschikbaar, u kunt nu weer inloggen');
                            print('<br><a href="../index.php">Klik hier om terug te gaan naar de hoofdpagina</a>');
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
