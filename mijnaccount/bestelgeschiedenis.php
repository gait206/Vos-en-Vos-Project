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
            define('THIS_PAGE', 'Mijnaccount');
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
                    $Klantnr = getKlantnr($link);

                    // Stel de tijdzone in (vaak vereist in PHP5 bij gebruik van datum/tijd functies)
                    if (function_exists('date_default_timezone_set')) {
                        date_default_timezone_set('Europe/Amsterdam');
                    }
                    // de code hieronder haalt alle bestelling op die niet "in behandeling" zijn.
                    $Klantnr = getKlantnr($link);
                    $result = mysqli_query($link, "SELECT bestelnr, besteldatum, bezorgdatum, opmerking,  status FROM Bestelling WHERE klantnr = '$Klantnr' AND status != 'In behandeling' AND betaald = 'ja'");
                    $bestelling = mysqli_fetch_assoc($result);
                   // de code hieronder print te tabel met bestellingen.
                    print("<table class='tablebestellingen'><th>Bestelnummer</th><th>Opmerking</th><th>Besteldatum</th><th>Bezorgdatum</th><th>Status</th>");
                    while ($bestelling) {
                        if (empty($bestelling["opmerking"]))
                            $bestelling["opmerking"] = "N.V.T";
                        print("<tr>"
                                //de eerste TD neemt het bestelnummer mee in de url, voor bestelling.php
                                . "<td><a href='bestelling.php?bestelnr=" . $bestelling["bestelnr"] . "' class='bestelnummer'>" . $bestelling["bestelnr"] . "</a></td>"
                                . "<td>" . $bestelling["opmerking"] . "</td>"
                                . "<td>" . date("d-m-Y", strtotime($bestelling["besteldatum"])) . "</td>"
                                . "<td>" . date("d-m-Y", strtotime($bestelling["bezorgdatum"])) . "</td>"
                                . "<td>" . $bestelling["status"] . "</td>"
                                . "</tr>");
                        $bestelling = mysqli_fetch_assoc($result);
                    }


                    print("</table>");
                    ?>
                </div>
            </div>
            <div>
                <form class="margin" action="mijnbestellingen.php" method="POST"><input class="bestelgeschiedenis" type="submit" name="mijn bestellingen" value="Mijn bestellingen"></form>
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
