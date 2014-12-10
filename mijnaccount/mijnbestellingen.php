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
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
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
            define('THIS_PAGE', 'mijnbestellingen');
            include('../menu.php');
            ?>

            <div class="content" id="main_content">
                <!--kijken of de login klopt-->
                <?php
//                if (!validToken($link)) {
//                    header('Location: ../index.php');
//                }
//                $email = getEmail($link);
                $email = "dick@gmail.com";
                var_dump($email);
                $result = mysqli_query($link, "SELECT * FROM Bestelling AS B JOIN Klant AS K ON K.klantnr = K.klantnr WHERE email = '$email' AND status ='kots'");
                $bestelling = mysqli_fetch_assoc($result);
                var_dump($bestelling);
                
                ?>
            </div>

            <div class="footer">

            </div>
        </div>
    </body>
</html>
<?php
mysqli_close($link);
?>
