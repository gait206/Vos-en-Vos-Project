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
                    <div class="forgot_password">
                    <?php
                    if(validToken($link)){
                        header('Location: ../index.php');
                    } else {
                        print('<h1>Wachtwoord Vergeten</h1>');
                        print('<p>Als u al geregistreerd bent op onze website en u uw wachtwoord bent vergeten kan u die hier opvragen.</p>');
                        
                        if(!empty($_POST["actie"])){
                            if(!empty($_POST["email"])){
                                $email = $_POST["email"];
                                // Vraag wachtwoord op
                                
                                
                                // code enzo
                            } else {
                                print('<p class="foutmelding">Je moet een email invullen!</p>');
                            }
                        }
                        
                        print('<form method="POST" action=""><input type="text" name="email"><input type="submit" class="forgot_button" name="actie" value="Wachtwoord Opvragen"></form>');
                        
                        
                    }
                    ?>
                    </div>
                </div>

                
            </div>

            <div class="footer">

            </div>

        </div>
        
    </body>
</html>
<?php
            mysqli_close($link);
            ?>
