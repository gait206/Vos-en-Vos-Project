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
        <link rel="stylesheet" type="text/css" href=" ../css/admin.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
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
            define('THIS_PAGE', 'mijnbestellingen');
            include('../menu.php');
            ?>

            <div class="content" id="main_content">
                <!--kijken of de login klopt-->
                <script>

                </script>
                <div class="body" id="main_content">
                    <?php
                    if (!validToken($link)) {
                        header('Location: ../index.php');
                    }
                    $_GET['bestelnr'];
                    
                    
                    $Klantnr = getKlantnr($link);
                    $bestelnr = $_GET["bestelnr"];
                    $Klantnr = getKlantnr($link);
                    $result = mysqli_query($link, "SELECT productnaam, B.productnr, aantal FROM bestelregel B JOIN product P ON B.productnr = P.productnr WHERE bestelnr='$bestelnr'");
                    $bestelling = mysqli_fetch_assoc($result);
                    print('<p class="bestelregelheader"> Bestellling: ' . $bestelnr . '</p>');
                    print("<table class='tablebestellingen'><th>productnaam</th><th>Productnummer</th><th>Aantal</th>");
                    while ($bestelling) {
                        print("<tr>"
                                . "<td>" . $bestelling["productnaam"] . "</td>"
                                . "<td>" . $bestelling["productnr"] . "</td>"
                                . "<td>" . $bestelling["aantal"] . "</td>"
                                . "</tr>");
                        $bestelling = mysqli_fetch_assoc($result);
                    }


                    print("</table>");
                    ?>
                </div>
            </div>
            <div>
                <form class="margin" action="bestelgeschiedenis.php" method="POST"><input class="bestelgeschiedenis" type="submit" name="mijn bestellingen" value="Bestelgeschiedenis"></form>
            </div>
            <div class="footer">
                <?php
                include "../footer.php";
                ?>
            </div>
        </div>
    </body>
</html>