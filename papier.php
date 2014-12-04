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
			define('THIS_PAGE', 'Papier');
			include('menu.php');
			?>

            <div class="content" id="main_content">
                <?php include("administratie/producten.php"); ?>
                
            </div>

            <div class="footer">

            </div>

        </div>
        <?php
        // put your code here
        ?>
        
    </body>
</html>
