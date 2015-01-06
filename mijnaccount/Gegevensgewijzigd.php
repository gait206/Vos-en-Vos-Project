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
        <link rel="stylesheet" type="text/css" href="../css/registratievoltooid.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
        <meta http-equiv="refresh" content="5;url=http://localhost:8080/index.php" />

    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="../plaatjes/logo.png">
                </div>
                <div class="login">
                    <?php
                    include('../login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
			define('THIS_PAGE', 'Home');
			include('../menu.php');
			?>

            <div class="content">
                <div class="body" id="main_content">
                    <div class="registratievoltooid">
                <?php
                print("U heeft uw gegevens met succes gewijzigd<br>");
                
                print("U word binnen 5 seconden doorverwezen naar de hoofdpagina");
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
