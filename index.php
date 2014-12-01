<?php
session_start();
include('functies.php');
$link = connectDB();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="plaatjes/logo.png">
                </div>
                <div class="login">
                    <?php
                    include('login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
			define('THIS_PAGE', 'Home');
			include('menu.php');
			?>

            <div class="content" id="main_content">
                <?php include("administratie/producten.php"); 
                        mysqli_close($link);?>
                
            </div>

            <div class="footer">

            </div>

        </div>
    </body>
</html>
