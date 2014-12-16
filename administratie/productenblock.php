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
        if (isset($_GET["subcategorie"])) {
            $subcategorie = $_GET["subcategorie"];
        } else {
            $subcategorie = array();
        }
        ?>
        <div class="navigator">


            <div class="navigatie">
                <form action="" method="get" id="select">
                    <input class="zoekinput" type="text" placeholder="Zoek" name="zoekbalk" <?php if(isset($_GET['zoekbalk'])){ print('value="'.$_GET['zoekbalk'].'"'); } ?>>
                    <input class="zoeksubmit" type="submit" value="Zoek" name="zoekknop"><br><br>
                    <h4>Selecteer subcategorie:</h4>
                    <input type="checkbox"  name="subcategorie[]"  value="Katrin" <?php if (in_array("Katrin", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Katrin<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Blanco" <?php if (in_array("Blanco", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Blanco<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Tana" <?php if (in_array("Tana", $subcategorie)) echo "checked ='checked'"; ?>onclick="this.form.submit()";>Tana<br>
                    <input type="checkbox"  name="subcategorie[]"  value="Eurotissue" <?php if (in_array("Eurotissue", $subcategorie)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Eurotissue<br><br>
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
                        <option value=5 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 5) echo "selected"; ?>>categorie (oplopend)</option>
                        <option value=6 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 6) echo "selected"; ?>>categorie (aflopend)</option>
                    </select>
                    </select>
                </form>

                <?php
                // code to get the right variables into the right place in the functions and such
				switch (THIS_PAGE){
                    case 'Papier'               : $switch = 0;
                        break;
                    case 'Dispensers'           : $switch = 1;
                        break;
                    case 'Reinigingsmiddelen'   : $switch = 2;
                        break;
                    case 'Schoonmaakmateriaal'  : $switch = 3;
                        break;
                    default : $switch = 4;
                }
				
                $query = "";
                if (isset($_GET['subcategorie']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop'])) {
	
                    if (isset($_GET['subcategorie'])) {
                        $checkbox = $_GET['subcategorie'];
                    } else {
                        $checkbox = array();
                    }
                    $switch = $_GET['prijs'];
                    $sort = $_GET['sort'];
					$query = base_query_generate(4);
                    $query = filter_query_generate($query, $switch, $checkbox);
                    if (isset($_GET['zoekknop']) || isset($_GET['zoekbalk'])) {
					if (!$_GET['zoekbalk'] == ""){
                        $query = search_query_generate($_GET['zoekbalk'], $query);
                    }}
                    $query = sort_query_generate($query, $sort);
                }
                ?>

            </div>
        </div>

        <div class="body" id="main_content">
            <?php
// Create connection
            $conn = connectDB();
            if (!(isset($_GET['subcategorie']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop']))) {
                if ($query == "") {
                    
					$query = "SELECT afbeelding, productnr, productnaam, omschrijving, subcategorie, prijs FROM product";

                }
            }
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
				?>
              
                <p class="aantalzoek">
				<?php
				if(!mysqli_num_rows($result) == 0){
				print("Aantal resultaten: " .mysqli_num_rows($result));
				}
				?>
                </p>
                
                <?php
                while ($row) {
                    print('<table class="headerblock">
					<tr height=0px></tr>
					<tr>
			  <td class="afbeeldingblock" colspan=2>');
			  
			  if($row['afbeelding'] == ""){
				  print('<img src="./plaatjes/logo.png">');
				  }
				  else{
				  print('<img src=' . $row['afbeelding'].'>');
				  }
				  print("</td></tr>
				  <tr>
              <td colspan=2 class=\"productnaamblock\">". '<a href="#" onclick="document.product.submit()">' . $row['productnaam'] . "	</a>"
			  .'<form name="product" method="GET" action="administratie/product.php" ><input type="hidden" name="productnr" value="' . $row["productnr"] . '"></form>'
			  ."</td>
			  </tr><tr>
			  <td colspan=2\">
              <div class=\"omschrijvingblock\">" . $row['omschrijving'] . "</div>
			  </td>
			  </tr><tr>"
			  
			  .'<td class="winkelmblock">'
			  . '<form action="winkelwagen.php" method="POST" >'
                        . '<input type="hidden" name="productnr" value="' . $row["productnr"] . '">'
						. '<input type="hidden" name="actie" value="toevoegen">'
                        . '<a class="tooltip-right" data-tooltip="Bestel"><input type="image" name="image" value="toevoegen" style="height:40px;" src="./plaatjes/winkelmandje.jpg" alt="Submit Form"></a></form>'.'
						</td>
              <td class="prijsblock">&euro; ' . number_format($row['prijs'],2,",",".") . "
			  <div class=\"prijskleinblock\">(&euro; ".prijsber($row['prijs'])." incl 21% BTW)</div>
			  </td></tr>");
                    $row = mysqli_fetch_assoc($result);
                }
				?>

        </table>
        <?php
		
		if (mysqli_num_rows($result) == 0){
		print("<p class=\"geenres\">Geen resultaten gevonden</p>");  
		}
		?>      
    </div>

</body>
</html>