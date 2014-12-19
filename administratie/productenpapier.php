<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="./css/producten.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <title></title>

    </head>
    <body>
        <?php
        $conn = connectDB();
        if (isset($_GET["subcategorie"])) {
            $subcategorie = $_GET["subcategorie"];
        } else {
            $subcategorie = array();
        }
        ?>
        <div class="navigator">


            <div class="navigatie">
                <form action="" method="get" id="select">
                    <input class="zoekinput" type="text" placeholder="Zoek" name="zoekbalk" <?php
                    if (isset($_GET['zoekbalk'])) {
                        print('value="' . $_GET['zoekbalk'] . '"');
                    }
                    ?>>
                    <input class="zoeksubmit" type="submit" value="Zoek" name="zoekknop"><br><br>
                    <h4>Selecteer subcategorie:</h4>
                    <input type="checkbox"  name="subcategorie[]"  value="Handdoekrollen" <?php if (in_array("Handdoekrollen", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Handdoekrollen<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Keukenrollen" <?php if (in_array("Keukenrollen", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Keukenrollen<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Poetsrollen" <?php if (in_array("Poetsrollen", $subcategorie)) echo "checked ='checked'"; ?>onclick="this.form.submit()";>Poetsrollen<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Servetten" <?php if (in_array("Servetten", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Servetten<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Toiletpapier" <?php if (in_array("Toiletpapier", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Toiletpapier<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Vouwhanddoeken" <?php if (in_array("Vouwhanddoeken", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Vouwhanddoeken<br><br>
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
                        <option value=1 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 1) echo "selected"; ?>>subcategorie (oplopend)</option>
                        <option value=2 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 2) echo "selected"; ?>>subcategorie (aflopend)</option>
                        <option value=3 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 3) echo "selected"; ?>>prijs (oplopend)</option>
                        <option value=4 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 4) echo "selected"; ?>>prijs (aflopend)</option>
                    </select>
                </form>

                <?php
                // code to get the right variables into the right place in the functions and such
                switch (THIS_PAGE) {
                    case 'Papier' : $switch = 0;
                        break;
                    case 'Dispensers' : $switch = 1;
                        break;
                    case 'Reinigingsmiddelen' : $switch = 2;
                        break;
                    case 'Schoonmaakmateriaal' : $switch = 3;
                        break;
                    default : $switch = 4;
                }

                $query = base_query_generate($switch);

                if (isset($_GET['subcategorie']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop'])) {

                    if (isset($_GET['subcategorie'])) {
                        $checkbox = $_GET['subcategorie'];
                    } else {
                        $checkbox = array();
                    }
                    $prijs = $_GET['prijs'];
                    $sort = $_GET['sort'];
                    $query = filter_query_generate($query, $prijs, $checkbox);
                    if (isset($_GET['zoekknop']) || isset($_GET['zoekbalk'])) {
                        if (!$_GET['zoekbalk'] == "") {
                            $query = search_query_generate($_GET['zoekbalk'], $query);
                        }
                    }
                    $query = sort_query_generate($query, $sort);
                }

                if (!empty($_GET["perpage"])) {
                    $perpage = $_GET["perpage"];
                } else {
                    $perpage = 20;
                }

                $resultcount = mysqli_query($conn, $query);
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

                if (!empty($_GET["action"])) {
                    if ($_GET["action"] == "<") {
                        if ($pages != 0) {
                            $pages = $_GET["pages"] - $perpage;
                        }
                    }else {
                        if (!($pages <= ($perpage * ($amount -1)))){
                            $pages = $_GET["pages"] + $perpage;
                        }else{
                            $pages = $perpage;
                        }
                    }
                }
                $query = limit_query_generate($pages, $query, $perpage);
                ?>

            </div>
        </div>

        <div class="body" id="main_content">
            <?php
// Create connection
//            if (!(isset($_GET['subcategorie']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop']))) {
//                if ($query == "") {
//
//                    $query = "SELECT * FROM product";
//                }
//            }
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
                if (!mysqli_num_rows($resultcount) == 0) {
                    print("Aantal resultaten: " . mysqli_num_rows($resultcount));
                }
                ?>
            </p>
            aantal producten per pagina: <select name="perpage" onchange="this.form.submit()" form="select">
                <option value="10"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 10) echo "selected"; ?>>10</option>
                <option value="20"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 20) {
                    echo "selected";
                } elseif (empty($_GET["perpage"])) {
                    echo 'selected';
                } ?>>20</option>
                <option value="25"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 25) echo "selected"; ?>>25</option>
                <option value="50"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 50) echo "selected"; ?>>50</option>
            </select>
            <table>
                <?php
                while ($row) {
                    print("<tr>
			  <td class=\"afbeelding\">");
                    if ($row['afbeelding'] == "") {
                        print("<img src=\"./plaatjes/logo.png\"");
                    } else {
                        print("<img src= " . $row['afbeelding'] . " ");
                    }
                    print("</td>
				<td class=\"productnaam\">" . '<a href="#" onclick="document.product.submit()">' . $row['productnaam'] . "	</a>"
                            . '<form name="product" method="GET" action="administratie/product.php" ><input type="hidden" name="productnr" value="' . $row["productnr"] . '"></form>'
                            . " <div class=\"omschrijving\">" . $row['omschrijving'] . "</div></td>
				<td class=\"winkelm\">"
                            . '<form action="winkelwagen.php" method="POST" >'
                            . '<input type="hidden" name="productnr" value="' . $row["productnr"] . '">'
                            . '<input type="hidden" name="actie" value="toevoegen">'
                            . '<a class="tooltip-right" data-tooltip="Bestel"><input type="image" name="actie" value="toevoegen" style="height:40px;" src="./plaatjes/winkelmandje.jpg" alt="Submit Form"></form></a></td>'
                            . '<td class="prijs">&euro; ' . number_format($row['prijs'], 2, ",", ".")
                            . '<div class="prijsklein"><br>(&euro; ' . prijsber($row['prijs']) . ' incl 21% BTW)</div></td> </tr>'
                            . '<tr><td colspan=4>  <img height=5px width=100% src="./plaatjes/line.png"></p>'
                            . '</td></tr>');
                    $row = mysqli_fetch_assoc($result);
                }
                ?>

            </table>
            <?php
            if (mysqli_num_rows($resultcount) == 0) {
                print("<p class=\"geenres\">Geen resultaten gevonden</p>");
            }
            ?>
            <input type="submit" name="action" value="<" form="select">
            <select name="pages" onchange="this.form.submit()" form="select">
                <?php
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
            if (!empty($_GET["pages"])) {
                print "<input type='hidden' name='ref' value='" . $_GET["pages"] . "' form='select' >";
            } else {
                print "<input type='hidden' name='ref' value='0' form='select' >";
            }
            ?>
            <input type="submit" name="action" value=">" form="select">
        </div>

    </body>
</html>
