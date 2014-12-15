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
        <link rel="stylesheet" type="text/css" href=" ../css/mijnbestellingen.css">
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
                <script>

                </script>
                <?php
                if (!validToken($link)) {
                    header('Location: ../index.php');   
                }
                $Klantnr = getKlantnr($link);
                $bestelnr = $_GET["bestelnr"];

                $Klantnr = getKlantnr($link);
                $result = mysqli_query($link, "SELECT bestelnr, productnr, aantal FROM bestelregel WHERE bestelnr='$bestelnr'");
                $bestelling = mysqli_fetch_assoc($result);

                print("<table><th>Bestelnummer</th><th>Productnummer</th><th>Aantal</th>");
                while ($bestelling) {
                    print("<tr>"
                            . "<td>" . $bestelling["bestelnr"] . "</td>"
                            . "<td>" . $bestelling["productnr"] . "</td>"
                            . "<td>" . $bestelling["aantal"] . "</td>"
                            . "</tr>");
                    $bestelling = mysqli_fetch_assoc($result);
                }


                print("</table>");
                ?>
                <a href="mijnbestellingen.php" class="bestelgeschiedenis">Mijn bestellingen</a>
            </div>

            <div class="footer">
                <?php
                include "../footer.php";
                ?>
            </div>
        </div>
    </body>
</html>