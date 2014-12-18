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
        <link rel="stylesheet" type='text/css' href=" ../css/admin.css">
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
                    function checkDelete() {
                        return confirm("Weet u zeker dat u deze besteling wilt annuleren?");
                    }
                </script>
                <div class="body" id="main_content">
                    <?php
                    if (!validToken($link)) {
                        header('Location: ../index.php');
                    }
                    $Klantnr = getKlantnr($link);
                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                        if ($actie == "Bestelling annuleren") {
                            $bestelnummer = $_POST["bestelnr"];
                            mysqli_query($link, 'UPDATE Bestelling SET status = "Geannuleerd", bezorgdatum = NULL WHERE bestelnr = "' . $bestelnummer . '";');
                        }
                    }

                    $Klantnr = getKlantnr($link);
                    $result = mysqli_query($link, "SELECT bestelnr, besteldatum, bezorgdatum, opmerking,  status FROM Bestelling WHERE klantnr = '$Klantnr' AND status ='In behandeling'");
                    $bestelling = mysqli_fetch_assoc($result);

                    print("<table class='tablebestellingen'><th>Bestelnummer</th><th>Opmerking</th><th>Besteldatum</th><th>Bezorgdatum</th><th>Status</th><th>Annuleren</th>");
                    while ($bestelling) {
                        if (empty($bestelling["opmerking"]))
                            $bestelling["opmerking"] = 'N.V.T';
                        print("<tr>"
                                . "<td><a href='bestelling.php?bestelnr=" . $bestelling["bestelnr"] . "' class='bestelnummer'>" . $bestelling["bestelnr"] . "</a></td>"
                                . "<td>" . $bestelling["opmerking"] . "</td>"
                                . "<td>" . $bestelling["besteldatum"] . "</td>"
                                . "<td>" . $bestelling["bezorgdatum"] . "</td>"
                                . "<td>" . $bestelling["status"] . "</td>"
                                . '<td class="Tableannuleer"><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="bestelnr" value="' . $bestelling["bestelnr"] . '"><input type="submit" name="actie" value="Bestelling annuleren" onClick="return checkDelete()"></form></td>'
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
<?php
mysqli_close($link);
?>
