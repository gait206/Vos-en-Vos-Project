<?php
session_start();
include('functies.php');
$link = connectDB();
$cookiename = 'winkelmandje';
if (!existCookie($cookiename)) {
    addCookie($cookiename, array());
	}
        
        include('login/loginscherm.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <a href="index.php"><img class="logo" src="plaatjes/logo.png"></a>
                </div>
                <div class="login">
                    <?php
                    include('login/loginscherm2.php');
                    ?>
                </div>
            </div>

            <?php
			define('THIS_PAGE', 'Home');
			include('menu.php');
			?>
			
            <div class="content" id="main_content">
                <?php include("administratie/productenblockhome.php"); 
                        ?>
                
            </div>

            <div class="footer">
            <?php
			include "footer.php";
			?>
            </div>
        </div>
    </body>
</html>
<?php
mysqli_close($link);
?>