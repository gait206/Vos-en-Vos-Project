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
                    <a href="../index.php"><img class="logo" src="../plaatjes/logo.png"></a>
                </div>
                <div class="login">
                    <?php
                    include('../login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
            define('THIS_PAGE', 'Admin');
            include('../menu.php');
            ?>

            <div class="content">
                <script>
                    function checkDelete() {
                        return confirm("Weet u zeker dat u deze gebruiker wilt verwijderen?");
                    }
                </script>
                <div class="body" id="main_content">
                    <?php
                    // zorgt ervoor dat alleen de Admin op de pagina mag komen
                    restrictedPage("Admin", $link);

                    // kijkt of er een actie is uitgevoerd
                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];

                        // word uitgevoerd als er wat moet worden verwijderd
                        if ($actie == "Verwijderen") {
                            $klantnr = $_POST["klantnr"];
                            // verwijderd de klant in de child table van gebruiker
                            mysqli_query($link, 'DELETE FROM klant WHERE klantnr = ' . $klantnr . '; ');
                            print(mysqli_error($link));
                            // verwijderd de gebruiker
                            mysqli_query($link, 'DELETE FROM gebruiker WHERE klantnr = ' . $klantnr . ';');
                            print(mysqli_error($link));
                        }

                        // word uitgevoerd als er wat bijgewerkt moet worden
                        if ($actie == "Bijwerken") {

                            // haalt alle opgestuurde variabelen op
                            $klantnr = $_POST["klantnr"];
                            $email = $_POST["email"];
                            $voornaam = $_POST["voornaam"];
                            $achternaam = $_POST["achternaam"];
                            $telnummer = $_POST["telnummer"];
                            $mobnummer = $_POST["mobnummer"];
                            $bedrijf = $_POST["bedrijf"];
                            $kvknummer = $_POST["kvknummer"];
                            $btwnummer = $_POST["btwnummer"];
                            $adres = $_POST['adres'];
                            $plaats = $_POST['plaats'];
                            $postcode = $_POST['postcode'];
                            $level = $_POST['level'];

                            // update de gegevens van de klant
                            mysqli_query($link, 'UPDATE klant k join gebruiker g on g.klantnr=k.klantnr and g.klantnr = "' . $klantnr . '" SET email = "' . $email . '",  voornaam = "' . $voornaam . '" , achternaam ="' . $achternaam . '" , telnummer="' . $telnummer . '"'
                                    . '  , mobnummer="' . $mobnummer . '" , bedrijfsnaam ="' . $bedrijf . '" , kvknummer ="' . $kvknummer . '" , btwnummer ="' . $btwnummer . '" , adres="' . $adres . '"'
                                    . ' , plaats="' . $plaats . '" , postcode="' . $postcode . '"  , level ="' . $level . '";');
                            print(mysqli_error($link));
                        }
                    }

                    // zorgt ervoor dat het variabel actie leeg is als er geen actie uitgevoerd moet worden
                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                    } else {
                        $actie = "";
                    }

                    // word uitgevoerd als er wat aangepast moet worden
                    if ($actie == "Aanpassen") {

                        // haalt alle informatie van een klant uit de database
                        $result = mysqli_query($link, 'SELECT * FROM klant k, gebruiker g WHERE k.klantnr = g.klantnr and g.klantnr ="' . $_POST["klantnr"] . '";');
                        $row = mysqli_fetch_assoc($result);

                        $email = $row["email"];
                        $voornaam = $row["voornaam"];
                        $achternaam = $row["achternaam"];
                        $telnummer = $row["telnummer"];
                        $mobnummer = $row["mobnummer"];
                        $bedrijf = $row["bedrijfsnaam"];
                        $kvknummer = $row["kvknummer"];
                        $btwnummer = $row["btwnummer"];
                        $adres = $row['adres'];
                        $plaats = $row['plaats'];
                        $postcode = $row['postcode'];
                        $level = $row['level'];

                        $waarde = "Bijwerken";

                        // zorgt ervoor dat alle informatie van de klant in een formulier word ingevuld
                        print('<table><form id="toevoegen" method="POST" action="">'
                                . '<tr><td>Email:</td><td><input type="text" name="email" value="' . $email . '"></td></tr>'
                                . '<tr><td>Voornaam:</td><td><input type="text" name="voornaam" value="' . $voornaam . '	"></td></tr>'
                                . '<tr><td>achternaam:</td><td><input type="text" name="achternaam" value="' . $achternaam . '"></td></tr>'
                                . '<tr><td>Telefoon nummer:</td><td><input type="text" name="telnummer" value="' . $telnummer . '"></td></tr>'
                                . '<tr><td>Mobiel nummer:</td><td><input type="text" name="mobnummer" value="' . $mobnummer . '"></td></tr>'
                                . '<tr><td>Bedrijf:</td><td><input type="text" name="bedrijf" value="' . $bedrijf . '"></td></tr>'
                                . '<tr><td>KVKnummer:</td><td><input type="text" name="kvknummer" value="' . $kvknummer . '"></td></tr>'
                                . '<tr><td>BTWnummer:</td><td><input type="text" name="btwnummer" value="' . $btwnummer . '"></td></tr>'
                                . '<tr><td>Adres:</td><td><input type="text" name="adres" value="' . $adres . '"><input type="text" name="plaats" value="' . $plaats . '"><input type="text" name="postcode" value="' . $postcode . '"></td></tr>'
                                . '<tr><td>Level:</td><td><input type="text" name="level" value="' . $level . '"></td></tr>'
                                . '<input type="hidden" name="klantnr" value="' . $row["klantnr"] . '">'
                                . '</form></table>'
                                . '<input form="toevoegen" type="submit" name="actie" class="button" value="' . $waarde . '">'
                        );
                    }

                    // word uitgevoerd als informatie van een klant bekeken moet worden
                    if ($actie == "bekijken") {
                        $klantnr = $_POST["klantnr"];

                        // haalt alle informatie van een klant uit de database
                        $result = mysqli_query($link, 'SELECT * FROM klant k, gebruiker g WHERE k.klantnr = g.klantnr and g.klantnr ="' . $_POST["klantnr"] . '";');
                        $row = mysqli_fetch_assoc($result);
                        $email = $row["email"];
                        $voornaam = $row["voornaam"];
                        $achternaam = $row["achternaam"];
                        $telnummer = $row["telnummer"];
                        $mobnummer = $row["mobnummer"];
                        $bedrijf = $row["bedrijfsnaam"];
                        $kvknummer = $row["kvknummer"];
                        $btwnummer = $row["btwnummer"];
                        $adres = $row['adres'];
                        $plaats = $row['plaats'];
                        $postcode = $row['postcode'];
                        $level = $row['level'];

                        // zorgt ervoor dat de data van de klant word weergegeven
                        print('<table>'
                                . '<tr><td>Email:</td><td>' . $email . '</td></tr>'
                                . '<tr><td>Voornaam:</td><td>' . $voornaam . '</td></tr>'
                                . '<tr><td>achternaam:</td><td>' . $achternaam . '</td></tr>'
                                . '<tr><td>Telefoon nummer:</td><td>' . $telnummer . '</td></tr>'
                                . '<tr><td>Mobiel nummer:</td><td>' . $mobnummer . '</td></tr>'
                                . '<tr><td>Bedrijf:</td><td>' . $bedrijf . '</td></tr>'
                                . '<tr><td>KVKnummer:</td><td>' . $kvknummer . '</td></tr>'
                                . '<tr><td>BTWnummer:</td><td>' . $btwnummer . '</td></tr>'
                                . '<tr><td>Adres:</td><td>' . $adres . " " . $plaats . " " . $postcode . '</td></tr>'
                                . '<tr><td>Level:</td><td>' . $level . '</td></tr>'
                                . '</table>'
                        );
                    }
                    ?>

                    <div class="header_administratie">Account zoeken</div>	
                    <table class="table">
                        <tr>
                        <form id="zoeken" method="get" action="">
                            </tr>
                            <tr>
                                <td>Zoeken : <input type="text" name="zoektext"	></td>
                                <td>E.g klantnummer,bestelnummer of transactie referentie</td>
                            </tr>
                            <tr>
                                <td><input form="zoeken" type="submit" class="button" name="zoeksubmit" value="Zoeken"></td>
                            </tr>
                        </form>
                    </table>

                    <?php
                    // maakt de basis query aan
                    $query = 'SELECT * FROM gebruiker g, klant k where g.klantnr =k.klantnr ';

                    // bouwt als er gezocht word verder op de basis query
                    if (isset($_GET["zoektext"])) {

                        $query = search_query_generate_account($_GET["zoektext"], $query);
                    }

                    // kijkt of er een aantal resultaten is ingesteld en gebruikt anders de standaard waarde
                    if (!empty($_GET["perpage"])) {
                        $perpage = $_GET["perpage"];
                    } else {
                        $perpage = 20;
                    }

                    // zorgt ervoor dat het aantal resultaten worden geteld
                    $resultcount = mysqli_query($link, $query);
                    $amount = amount_per_page($resultcount, $perpage);

                    
                    if (!empty($_GET["pages"])) {
                        if ($_GET["pages"] == $_GET["ref"]) {
                            $pages = 0;
                        } else {
                            $pages = $_GET["pages"];
                        }
                    } else {
                        $pages = 0;
                    }

                    // zorgt ervoor dat je naar de volgende of de vorige pagina gaat als je op een van de pijlen klikt
                    if (!empty($_GET["action"])) {
                        if ($_GET["action"] == "<") {
                            if ($pages != 0) {
                                $pages = $_GET["pages"] - $perpage;
                            }
                        } else {
                            if (!(($pages / $perpage) >= $amount)) {
                                $pages = $_GET["pages"] + $perpage;
                            } else {
                                $pages = $perpage;
                            }
                        }
                    }

                    // zorgt ervoor dat er een limiet word ingesteld op de query en voert de query uit
                    $query = limit_query_generate($pages, $query, $perpage);
                    $result = mysqli_query($link, $query);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <!-- Zorgt er voor dat een bepaalde waarde geselecteerd blijft als deze geselecteerd is
                    -->
                    <div class="aantalzoek">
                        aantal producten per pagina: <select name="perpage" onchange="this.form.submit()" form="select">
                            <option value="10"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 10) echo "selected"; ?>>10</option>
                            <option value="20"<?php
                    if (!empty($_GET["perpage"]) && $_GET["perpage"] == 20) {
                        echo "selected";
                    } elseif (empty($_GET["perpage"])) {
                        echo 'selected';
                    }
                    ?>>20</option>
                            <option value="25"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 25) echo "selected"; ?>>25</option>
                            <option value="50"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 50) echo "selected"; ?>>50</option>
                        </select>




<?php
// geeft het aantal resultaten weer
if (!mysqli_num_rows($resultcount) == 0) {
    print(" Aantal resultaten: " . mysqli_num_rows($resultcount));
}
?>

                    </div>
<?php
// zorgt ervoor dat de tabel met alle klanten word weergegeven
print('<table class="table_administratie"><tr><th>Email</th><th>Naam</th><th>telefoon</th><th>bedrijfsnaam</th><th>plaats</th><th>level</th><th>Verwijderen</th><th>Aanpassen</th><th>Bekijken</th></tr>');
while ($row) {
    print('<tr><td>' . $row["email"] . '</td>'
            . '<td>' . $row["voornaam"] . " " . $row["achternaam"] . '</td>'
            . '<td>' . $row["telnummer"] . '</td>'
            . '<td>' . $row["bedrijfsnaam"] . '</td>'
            . '<td>' . $row["plaats"] . '</td>'
            . '<td>' . $row["level"] . '</td>'
            . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="klantnr" value="' . $row["klantnr"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete()"></form></td>'
            . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="klantnr" value="' . $row["klantnr"] . '"><input type="submit" name="actie" value="Aanpassen">	</form></td>'
            . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="klantnr" value="' . $row["klantnr"] . '"><input type="submit" name="actie" value="bekijken"></form></td></tr>');
    $row = mysqli_fetch_assoc($result);
}
print("</table>");
?>
                    <?php
                    if (($pages / $perpage + 1) != 1) {
                        print('<input type="submit" name="action" value="<" form="select">');
                    }
                    ?>	
                    <select name="pages" onchange="this.form.submit()" form="select">
                    <?php
                    // zorgt ervoor dat het aantal pagina's word weergegeven en selecteert de pagina waarop je bent
                    for ($i = 0; $i < $amount; $i++) {
                        $x = $i + 1;
                        if ($pages == ($i * $perpage)) {
                            print('<option value="' . $i * $perpage . '" selected >' . $x . '</option>');
                        } else {
                            print('<option value="' . $i * $perpage . '" >' . $x . '</option>');
                        }
                    }
                    ?>
                    </select>
                        <?php
                        // word uitgevoerd als het aantal pagina's is weergegeven
                        if (!empty($_GET["pages"])) {
                            print "<input type='hidden' name='ref' value='" . $_GET["pages"] . "' form='select' >";
                        } else {
                            print "<input type='hidden' name='ref' value='0' form='select' >";
                        }
                        ?>
                    <?php
                    //zorgt ervoor dat de pijl om naar de volgende pagina word weergegeven of niet
                    if (($pages / $perpage + 1) != round($amount)) {
                        print('<input type="submit" name="action" value=">" form="select">');
                    }
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
