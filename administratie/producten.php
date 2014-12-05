<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/producten.css">
        <title></title>

    </head>
    <body>
        <?php
        if (isset($_GET["merk"])) {
            $merk = $_GET["merk"];
        } else {
            $merk = array();
        }
        ?>
        <div class="navigator">


            <div class="navigatie">
                <form action="index.php" method="get" id="select">
                    <input class="zoekinput" type="text" placeholder="Zoek" name="zoekbalk" <?php
                    if (isset($_GET['zoekbalk'])) {
                        print('value="' . $_GET['zoekbalk'] . '"');
                    }
                    ?>>
                    <input class="zoeksubmit" type="submit" value="Zoek" name="zoekknop"><br><br>
                    <h4>Selecteer merk(en):</h4>
                    <input type="checkbox"  name="merk[]"  value="Katrin" <?php if (in_array("Katrin", $merk)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Katrin<br>
                    <input type="checkbox"  name="merk[]"  value="Blanco" <?php if (in_array("Blanco", $merk)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Blanco<br>
                    <input type="checkbox"  name="merk[]"  value="Tana" <?php if (in_array("Tana", $merk)) echo "checked ='checked'"; ?>onclick="this.form.submit()";>Tana<br>
                    <input type="checkbox"  name="merk[]"  value="Eurotissue" <?php if (in_array("Eurotissue", $merk)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Eurotissue<br><br>
                    <h4>Selecteer prijscategorie:</h4>
                    <select name="prijs" form="select" onchange="this.form.submit()">
                        <option value=0 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 0) echo "selected"; ?>>Selecteer prijs </option>
                        <option value=1 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 1) echo "selected"; ?>>< 10.00</option>
                        <option value=2 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 2) echo "selected"; ?>>10.00 ... 20.00</option>
                        <option value=3 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 3) echo "selected"; ?>>20.00 ... 30.00</option>
                        <option value=4 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 4) echo "selected"; ?>>30.00 ... 50.00</option>
                        <option value=5 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 5) echo "selected"; ?>>50.00 ... 75.00</option>
                        <option value=6 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 6) echo "selected"; ?>>75.00 ... 100.00</option>
                        <option value=7 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 7) echo "selected"; ?>>100.00 ... 200.00</option>
                        <option value=8 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 8) echo "selected"; ?>>> 200.00</option>
                    </select><br><br>
                    <h4>Sorteer op:</h4>
                    <select name="sort" form="select" onchange="this.form.submit()">
                        <option value=0 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 0) echo "selected"; ?>>Niet gesorteerd</option>
                        <option value=1 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 1) echo "selected"; ?>>merk (oplopend)</option>
                        <option value=2 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 2) echo "selected"; ?>>merk (aflopend)</option>
                        <option value=3 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 3) echo "selected"; ?>>prijs (oplopend)</option>
                        <option value=4 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 4) echo "selected"; ?>>prijs (aflopend)</option>
                    </select>
                </form>

                <?php
                // code to get the right variables into the right place in the functions and such
                switch (THIS_PAGE){
                    case 'Papier'               : $switch = 0;
                        break;
                    case 'Dispencers'           : $switch = 1;
                        break;
                    case 'Reinigingsmiddelen'   : $switch = 2;
                        break;
                    case 'Schoonmaakmateriaal'  : $switch = 3;
                        break;
                    default : $switch = 4;
                }
				
                $query = base_query_generate($switch);
				
                if (isset($_GET['merk']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop'])) {

                    if (isset($_GET['merk'])) {
                        $checkbox = $_GET['merk'];
                    } else {
                        $checkbox = array();
                    }
                    $switch = $_GET['prijs'];
                    $sort = $_GET['sort'];
                    $query = filter_query_generate($query, $switch, $checkbox);
                    if (isset($_GET['zoekknop']) || isset($_GET['zoekbalk'])) {
                        if (!$_GET['zoekbalk'] == "") {
                            $query = search_query_generate($_GET['zoekbalk'], $query);
                        }
                    }
                    $query = sort_query_generate($query, $sort);
                }
                ?>

            </div>
        </div>

        <div class="body" id="main_content">
            <?php
// Create connection
            $conn = connectDB();
            if (!(isset($_GET['merk']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop']))) {
                if ($query == "") {

                    $query = "SELECT afbeelding, productnr, productnaam, omschrijving, merk, prijs FROM product";
                }
            }
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            ?>

            <?php
            print("<table><tr>
			  <td class=\"header\"><strong><!--Afbeelding--></strong></td>
              <td class=\"header\"><strong><!--Titel/omschrijving--></strong></td>    
			  <td class=\"header\"><strong><!--Winkelmandje--></strong></td>   
              <td class=\"header\"><strong><!--Prijs--></strong></td>
              </tr>");
            ?>

            <p class="aantalzoek">
                <?php
                if (!mysqli_num_rows($result) == 0) {
                    print("Aantal resultaten: " . mysqli_num_rows($result));
                }
                ?>
            </p>
            <?php
            while ($row) {
                print("<tr>
			  <td class=\"afbeelding\">");
                if ($row['afbeelding'] == "") {
                    print("<img src=\"../plaatjes/logo.png\"");
                } else {
                    print("<img src= " . $row['afbeelding'] . " ");
                }
                print("</td>
              <td class=\"productnaam\">" . $row['productnaam'] . "
              <div class=\"omschrijving\">" . $row['omschrijving'] . "</div></td>
			  <td class=\"winkelm\">" 
                        . '<form action="winkelwagen.php" method="POST" >'
                        . '<input type="hidden" name="productnr" value="' . $row["productnr"] . '">'
                        . '<input type="image" name="actie" value="toevoegen" src="../plaatjes/winkelmandje.jpg" alt="Submit Form"></form></td>'
                        . '<td class=\"prijs\">&euro;' . number_format($row['prijs'], 2)
                        . '<div class=\"prijsklein\"><br>(&euro;"' . prijsber($row['prijs']) . ' incl 21% BTW)</div></td> </tr>'
                        . '<tr><td colspan=4>  <img height=5px width=100% src=\"../plaatjes/line.png\"></p>'
                        . '</td></tr>');
                $row = mysqli_fetch_assoc($result);
            }
            ?>

        </table>
        <?php
        if (mysqli_num_rows($result) == 0) {
            print("<p class=\"geenres\">Geen resultaten gevonden</p>");
        }
        ?>      
    </div>

</body>
</html>
