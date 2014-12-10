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
        <link rel="stylesheet" type="text/css" href=" ../css/mijnbestelling.css">
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
                    function checkDelete() {
                        return confirm("Weet u zeker dat u deze besteling wilt annuleren?");
                    }
                </script>
                <?php
                if (!validToken($link)) {
                    header('Location: ../index.php');
                }
                $email = getEmail($link);
                if (!empty($_POST["actie"])) {
                    $actie = $_POST["actie"];
                    if ($actie == "Bestelling annuleren") {
                        $bestelnummer = $_POST["bestelnr"];
                        mysqli_query($link, 'UPDATE Bestelling SET status = "Geannuleerd" WHERE bestelnr = "' . $bestelnummer . '";');
                    }
                }

                $email = getEmail($link);
                $result = mysqli_query($link, "SELECT bestelnr, besteldatum, bezorgdatum, opmerking,  status FROM Bestelling AS B JOIN Klant AS K ON K.klantnr = K.klantnr WHERE email = '$email' AND status ='In behandeling'");
                $bestelling = mysqli_fetch_assoc($result);

                print("<table><th>Bestelnummer</th><th>Opmerking</th><th>Besteldatum</th><th>Bezorgdatum</th><th>Status</th>");
                while ($bestelling) {
                    print("<tr>"
                            . "<td><a href='bestelling.php?bestelnr=" . $bestelling["bestelnr"] . "'>" . $bestelling["bestelnr"] . "</a></td>"
                            . "<td>" . $bestelling["opmerking"] . "</td>"
                            . "<td>" . $bestelling["besteldatum"] . "</td>"
                            . "<td>" . $bestelling["bezorgdatum"] . "</td>"
                            . "<td>" . $bestelling["status"] . "</td>"
                            . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="bestelnr" value="' . $bestelling["bestelnr"] . '"><input type="submit" name="actie" value="Bestelling annuleren" onClick="return checkDelete()"></form></td>'
                            . "</tr>");
                    $bestelling = mysqli_fetch_assoc($result);
                }


                print("</table>");
                ?>
                <a href="Bestelgeschiedenis.php">Bestelgeschiedenis</a>
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
