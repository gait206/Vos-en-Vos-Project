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
            define('THIS_PAGE', 'wachtwoordveranderen');
            include('../menu.php');
            ?>

            <div class="content">
                <div class="body" id="main_content">
                    <div class="forgot_password">
                        <?php
                        if (!empty($_GET["email"])) {
                            $email = $_GET["email"];
                            $stmt = mysqli_prepare($link, 'DELETE FROM recovery WHERE email = ?;');
                            mysqli_stmt_bind_param($stmt, 's', $email);
                            mysqli_stmt_execute($stmt);
                            header('Location: index.php');
                        } else {
                            print('<h1>Deze link is niet geldig</h1>');
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
