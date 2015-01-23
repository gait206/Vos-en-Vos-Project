<?php
session_start();
include('../functies.php');
$link = connectDB();

if (!empty($_GET["email"])) {
                            $email = $_GET["email"];
                            
                            // selecteert het klantnr uit de database
                            $stmt3 = mysqli_prepare($link, 'SELECT klantnr FROM gebruiker WHERE email = ?;');
                                    mysqli_stmt_bind_param($stmt3, 's', $email);
                                    mysqli_stmt_execute($stmt3);
                                    mysqli_stmt_bind_result($stmt3, $klantnr);
                                    $result2 = mysqli_stmt_fetch($stmt3);
                                    mysqli_stmt_close($stmt3);
                            
                            // verwijderd de gebruiker uit de tabel recovery
                            $stmt = mysqli_prepare($link, 'DELETE FROM recovery WHERE klantnr = ?;');
                            mysqli_stmt_bind_param($stmt, 'i', $klantnr);
                            mysqli_stmt_execute($stmt);
                            header('Location: ../index.php');
                        }
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
                        // kijkt of de email is meegestuurd
                         if (empty($_GET["email"])) {
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
