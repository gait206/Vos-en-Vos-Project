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
        <link rel="stylesheet" type="text/css" href="../css/admin.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
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
            define('THIS_PAGE', 'Bestellingbeheer');
            include('../menu.php');
            ?>

            <div class="content">
                <div class="body" id="main_content">
                    <?php
                    restrictedPage("Admin", $link);

                    
                    
                    if (!empty($_GET["actie"])) {
                        $actie = $_GET["actie"];
                        if ($actie == "Verwijderen") {
                            $bestelnr = $_GET["bestelnr"];
                            $productnr = $_GET["productnr"];
                            mysqli_query($link, 'DELETE FROM bestelregel WHERE bestelnr = "' . $bestelnr . '" AND productnr = "' . $productnr . '";');
                            print(mysqli_error($link));
                        }

                        if ($actie == "Bijwerken") {
                            $bestelnr = $_GET["bestelnr"];
                            $productnr = $_GET["productnr"];
                            $aantal = $_GET["aantal"];
                            mysqli_query($link, 'UPDATE bestelregel SET aantal = "' . $aantal . '" WHERE bestelnr = "' . $bestelnr . '" AND productnr = "' . $productnr . '";');
                            print(mysqli_error($link));
                        }

                        if (!empty($_GET["actie"])) {
                            $actie = $_GET["actie"];
                        } else {
                            $actie = "";
                        }

                        if ($actie == "Aanpassen") {
                            $bestelnr = $_GET["bestelnr"];
                            $productnr = $_GET["productnr"];
                            $result = mysqli_query($link, 'SELECT aantal FROM bestelregel WHERE bestelnr ="' . $bestelnr . '" AND productnr = "' . $productnr . '";');
                            $row = mysqli_fetch_assoc($result);
                            $aantal = $row["aantal"];

                            $waarde = "Bijwerken";
                            print('<table><form id="toevoegen" method="GET" action="">'
                                    . '<tr><td>Aantal:</td><td><input type="hidden" name="productnr" value="'.$productnr.'"><input type="number" name="aantal" value="' . $aantal . '"><input type="hidden" name="bestelnr" value="' . $bestelnr . '"></td></tr>'
                                    . '</form></table>'
                                    . '<input form="toevoegen" type="submit" name="actie" class="button" value="' . $waarde . '">'
                            );
                        }
                        
                        if ($actie == "Toevoegen") {
                            $bestelnr = $_GET["bestelnr"];
                            
                            $waarde = "Product Toevoegen";
                            print('<table><form id="toevoegen" method="GET" action="">'
                                    . '<tr><td>Product Nummer</td><td><input type="number" name="productnr" placeholder="Productnr"></td></tr>'
                                    . '<tr><td>Aantal:</td><td><input type="number" name="aantal" placeholder="aantal"><input type="hidden" name="bestelnr" value="' . $bestelnr . '"></td></tr>'
                                    . '</form></table>'
                                    . '<input form="toevoegen" type="submit" name="actie" class="button" value="' . $waarde . '">'
                            );
                        }
                        
                        if ($actie == "Product Toevoegen") {
                            mysqli_query($link, 'INSERT INTO bestelregel VALUES("'.$_GET["bestelnr"].'","'.$_GET["productnr"].'","'.$_GET["aantal"].'");');
                            print(mysqli_error($link));
                        }
                    }

                    $bestelnr = $_GET["bestelnr"];
                    $result = mysqli_query($link, 'SELECT * FROM bestelregel b JOIN product p ON b.productnr = p.productnr WHERE bestelnr = "' . $bestelnr . '";');
                    $row = mysqli_fetch_assoc($result);

                    $result2 = mysqli_query($link, 'SELECT * FROM klant WHERE klantnr = ( SELECT klantnr FROM bestelling WHERE bestelnr = "'.$bestelnr.'");');
                    $row2 = mysqli_fetch_assoc($result2);
                    
                    print('<table><tr><td><form action="bestellingbeheer.php" method="POST"><input type="submit" name="terug" class="button" value="Terug naar overzicht"></form></td></tr>'
                            . '<tr><td>Bestelnr: </td><td>'.$bestelnr.'</td></tr>'
                            . '<tr><td>Klantnr: </td><td>'.$row2["klantnr"].'</td></tr>'
                            . '<tr><td>Bedrijfsnaam: </td><td>'.$row2["bedrijfsnaam"].'</td></tr>'
                            . '<tr><td>Telefoon nummer: </td><td>'.$row2["telnummer"].'</td></tr>');
                    
                    $result3 = mysqli_query($link, 'SELECT * FROM anderadres WHERE bestelnr = "'.$bestelnr.'";');
                    
                    if(mysqli_num_rows($result3) == 1){
                    
                        $row3 = mysqli_fetch_assoc($result3);
                            print('<tr><td>Plaats: </td><td>'.$row3["plaats"].'</td></tr>'
                            . '<tr><td>Adres: </td><td>'.$row3["adres"].'</td></tr>'
                            . '<tr><td>Postcode: </td><td>'.$row3["postcode"].'</td></tr>');
                    } else {
                        print('<tr><td>Plaats: </td><td>'.$row2["plaats"].'</td></tr>'
                            . '<tr><td>Adres: </td><td>'.$row2["adres"].'</td></tr>'
                            . '<tr><td>Postcode: </td><td>'.$row2["postcode"].'</td></tr>');
                    }
                    
                    $result4 = mysqli_query($link, 'SELECT opmerking FROM bestelling WHERE bestelnr = "'.$bestelnr.'";');
                    $row4 = mysqli_fetch_assoc($result4);
                    
                    print('<tr><td>Opmerking:</td><td>'.$row4["opmerking"].'</td></tr>'
                            . '<tr><td></td><td><form action="" method="GET" class="table_administratie_button"><input type="hidden" name="bestelnr" value="'.$bestelnr.'"><input type="submit" name="actie" value="Toevoegen"></form></td></tr></table>');
                    
                    
                    print('<table class="table_administratie"><tr><th>Productnr</th><th>Product naam</th><th>Aantal</th><th>Verwijderen</th><th>Aanpassen</th></tr>');
                    while ($row) {
                        print('<tr><td>' . $row["productnr"] . '</td>'
                                . '<td>' . $row["productnaam"] . '</td>'
                                . '<td>' . $row["aantal"] . '</td>'
                                . '<td><form action="" method="GET" class="table_administratie_button" ><input type="hidden" name="bestelnr" value="' . $_GET["bestelnr"] . '"><input type="hidden" name="productnr" value="' . $row["productnr"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete()"></form></td>'
                                . '<td><form action="" method="GET" class="table_administratie_button" ><input type="hidden" name="bestelnr" value="' . $_GET["bestelnr"] . '"><input type="hidden" name="productnr" value="' . $row["productnr"] . '"><input type="submit" name="actie" value="Aanpassen">	</form></td></tr>');
                        $row = mysqli_fetch_assoc($result);
                    }
                    print("</table>");
                    ?>

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
