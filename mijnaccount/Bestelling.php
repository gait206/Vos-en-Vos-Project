<?php
session_start();
include('../functies.php');
$link = connectDB();

if (!validToken($link)) {
    header('Location: ../index.php');
    die();
}
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
                    //de code hieronder zorgt ervoor dat een klant niet in de url het bestelnummer kan aanpassen.
                    $klantnr = getKlantnr($link);
                    $bestelnr = $_GET["bestelnr"];

                    $crosscheck = mysqli_query($link, "SELECT * FROM bestelling WHERE klantnr ='" . $klantnr . "'AND bestelnr='" . $bestelnr . "';");
                    $row = mysqli_fetch_assoc($crosscheck);
                    if (mysqli_num_rows($crosscheck) == 0) {
                        print("Er is geen bestelling gevonden met bestelnummer " . $bestelnr);
                    } else {
                        // hieronder worden de bestelregels opgheaald aan de hand van het bestelnummer.
                        // prepare and bind
                        mysqli_close($link);
                        $link = connectDB();
                        $stmt2 = mysqli_prepare($link, "SELECT productnaam, B.productnr, aantal FROM bestelregel B JOIN product P ON B.productnr = P.productnr WHERE bestelnr=?");
                        mysqli_stmt_bind_param($stmt2, "i", $bestelnr);
                        mysqli_stmt_execute($stmt2);
                        mysqli_stmt_bind_result($stmt2, $productnaam, $productnr, $aantal);

                        $result = mysqli_stmt_fetch($stmt2);

                        //deze code print de tabel met bestelregels.
                        print('<p class="bestelregelheader"> Bestellling: ' . $bestelnr . '</p>');
                        print("<table class='tablebestellingen'><th>productnaam</th><th>Productnummer</th><th>Aantal</th>");
                        while ($result) {
                            print("<tr>"
                                    . "<td>" . $productnaam . "</td>"
                                    . "<td>" . $productnr . "</td>"
                                    . "<td>" . $aantal . "</td>"
                                    . "</tr>");
                            $result = mysqli_stmt_fetch($stmt2);
                        }
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