<?php
session_start();
include('../functies.php');
$link = connectDB();

// Alleen een Admin user mag op deze pagina komen
                    restrictedPage("Admin", $link);
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
                    // Bevestiging voor het verwijderen van een product
                    function checkDelete() {
                        return confirm("Weet u zeker dat u dit product wilt verwijderen?");
                    }
                </script>
                <div class="body" id="main_content">

                    <?php
                    // Haalt de ingevulde gegevens uit het formulier en voert deze uit in de SQL query
                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                        if ($actie == "Toevoegen") {
                            $productnr = $_POST["productnr"];
                            $productnaam = $_POST["productnaam"];
                            $categorie = $_POST["categorie"];
                            $subcategorie = $_POST["subcategorie"];
                            $merk = $_POST["merk"];
                            $omschrijving = $_POST["omschrijving"];
                            $voorraad = $_POST["voorraad"];
                            $prijs = $_POST["prijs"];
                            $afbeelding = $_POST["afbeelding"];
                            $EAN = $_POST["EAN"];
                            $kleur = $_POST["kleur"];
                            $materiaal = $_POST["materiaal"];
                            $hoogte = $_POST["hoogte"];
                            $breedte = $_POST["breedte"];
                            $lengte = $_POST["lengte"];
                            $fabrikant = $_POST["fabrikant"];
                            $inhoud = $_POST["inhoud"];
                            $verpakking = $_POST["verpakking"];
                            $certificaten = $_POST["certificaten"];
                            
                            // SQL query die de ingevulde waardes in de database zet
                            if (!empty($productnr)) {
                                mysqli_query($link, "INSERT INTO product(productnr,productnaam,categorie,subcategorie,merk,omschrijving,voorraad,prijs,afbeelding,EAN,kleur,materiaal,hoogte,breedte,lengte,fabrikant,inhoud,verpakking,certificaten) VALUES('" . $productnr . "',' " . $productnaam . "', '" . $categorie . "', '" . $subcategorie . "', '" . $merk . "','" . $omschrijving . "', '" . $voorraad . "', '" . $prijs . "', '" . $afbeelding . "', '" . $EAN . "', '" . $kleur . "', '" . $materiaal . "', '" . $hoogte . "', '" . $breedte . "', '" . $lengte . "', '" . $fabrikant . "', '" . $inhoud . "', '" . $verpakking . "', '" . $certificaten . "');");
                                print(mysqli_error($link));
                            }
                        }
                        
                        // SQL query die na goedkeuring een product uit de database verwijderd
                        if ($actie == "Verwijderen") {
                            $productnr = $_POST["productnr"];
                            mysqli_query($link, 'DELETE FROM product WHERE productnr = "' . $productnr . '";');
                        }
                        
                        // Haalt de aangepaste gegevens uit het formulier en voert deze uit in de SQL query
                        if ($actie == "Bijwerken") {
                            $productnr = $_POST["productnr"];
                            $productnaam = $_POST["productnaam"];
                            $categorie = $_POST["categorie"];
                            $subcategorie = $_POST["subcategorie"];
                            $merk = $_POST["merk"];
                            $omschrijving = $_POST["omschrijving"];
                            $voorraad = $_POST["voorraad"];
                            $prijs = $_POST["prijs"];
                            $afbeelding = $_POST["afbeelding"];
                            $EAN = $_POST["EAN"];
                            $kleur = $_POST["kleur"];
                            $materiaal = $_POST["materiaal"];
                            $hoogte = $_POST["hoogte"];
                            $breedte = $_POST["breedte"];
                            $lengte = $_POST["lengte"];
                            $fabrikant = $_POST["fabrikant"];
                            $inhoud = $_POST["inhoud"];
                            $verpakking = $_POST["verpakking"];
                            $certificaten = $_POST["certificaten"];
                            
                            // SQL query die de aangepaste waardes in de database zet
                            mysqli_query($link, 'UPDATE product SET productnr = "' . $productnr . '", productnaam = "' . $productnaam . '", categorie = "' . $categorie . '", subcategorie = "' . $subcategorie . '", merk = "' . $merk . '", omschrijving = "' . $omschrijving . '", voorraad = "' . $voorraad . '", prijs = "' . $prijs . '", afbeelding = "' . $afbeelding . '", EAN = "' . $EAN . '", kleur = "' . $kleur . '", materiaal = "' . $materiaal . '", hoogte = "' . $hoogte . '", breedte = "' . $breedte . '", lengte = "' . $lengte . '", fabrikant = "' . $fabrikant . '", inhoud = "' . $inhoud . '", verpakking = "' . $verpakking . '", certificaten = "' . $certificaten . '"  WHERE productnaam = "' . $productnaam . '";');
                            print(mysqli_error($link));
                        }
                    }
                    
                    // Kijkt naar welke actie er uitgevoerd moet worden (Toevoegen, verwijderen of aanpassen)
                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                    } else {
                        $actie = "";
                    }


                    // Als de actie aanpassen is haalt die de gegevens uit de database met desbetreffende productnr
                    // Zo niet dan laat hij de inputvelden leeg
                    if ($actie == "Aanpassen") {
                        $productnr = $_POST["productnr"];
                        $result = mysqli_query($link, 'SELECT * FROM product WHERE productnr ="' . $productnr . '";');
                        $row = mysqli_fetch_assoc($result);
                        $productnr = $row["productnr"];
                        $productnaam = $row["productnaam"];
                        $categorie = $row["categorie"];
                        $subcategorie = $row["subcategorie"];
                        $merk = $row["merk"];
                        $omschrijving = $row["omschrijving"];
                        $voorraad = $row["voorraad"];
                        $prijs = $row["prijs"];
                        $afbeelding = $row["afbeelding"];
                        $EAN = $row["EAN"];
                        $kleur = $row["kleur"];
                        $materiaal = $row["materiaal"];
                        $hoogte = $row["hoogte"];
                        $breedte = $row["breedte"];
                        $lengte = $row["lengte"];
                        $fabrikant = $row["fabrikant"];
                        $inhoud = $row["inhoud"];
                        $verpakking = $row["verpakking"];
                        $certificaten = $row["certificaten"];
                        $waarde = "Bijwerken";
                    } else {
                        $productnr = "";
                        $productnaam = "";
                        $categorie = "";
                        $subcategorie = "";
                        $merk = "";
                        $omschrijving = "";
                        $voorraad = "";
                        $prijs = "";
                        $afbeelding = "";
                        $EAN = "";
                        $kleur = "";
                        $materiaal = "";
                        $hoogte = "";
                        $breedte = "";
                        $lengte = "";
                        $fabrikant = "";
                        $inhoud = "";
                        $verpakking = "";
                        $certificaten = "";
                        $waarde = "Toevoegen";
                    }
                    
                    // Formulier voor het toevoegen en aanpassen van producten
                    print('<div class="header_administratie">Product toevoegen</div>');
                    print('<table class="table">');
                    print('<form id="toevoegen" method="post" action=""');
                    print('<tr><td>Productnr:</td><td><input type="text" name="productnr" value="' . $productnr . '"><td></td>      <td colspan=2>Extra informatie</td>    <td>Fabrikant:</td><td><input type="text" name="fabrikant" value="' . $fabrikant . '"></tr>');
                    print('<tr><td>Productnaam:</td><td><input type="text" name="productnaam" value="' . $productnaam . '"><td></td>      <td>Kleur:</td><td><input type="text" name="kleur" value="' . $kleur . '">     <td>Inhoud:</td><td><input type="text" name="inhoud" value="' . $inhoud . '"></tr>');
                    print('<tr><td>Categorie:</td><td><input type="text" name="categorie" value="' . $categorie . '"><td></td>      <td>Materiaal:</td><td><input type="text" name="materiaal" value="' . $materiaal . '">     <td>Verpakking:</td><td><input type="text" name="verpakking" value="' . $verpakking . '"></tr>');
                    print('<tr><td>Subcategorie:</td><td><input type="text" name="subcategorie" value="' . $subcategorie . '"><td></td>      <td>Hoogte:</td><td><input type="text" name="hoogte" value="' . $hoogte . '">     <td>Certificaten:</td><td><input type="text" name="certificaten" value="' . $certificaten . '"></tr>');
                    print('<tr><td>Merk:</td><td><input type="text" name="merk" value="' . $merk . '"><td></td>      <td>Breedte:</td><td><input type="text" name="breedte" value="' . $breedte . '"><td>Omschrijving:</td><td rowspan=6><textarea rows=6 cols=50 name="omschrijving">' . $omschrijving . '</textarea></tr>');
                    print('<tr><td>Voorraad:</td><td><input type="text" name="voorraad" value="' . $voorraad . '"><td></td>      <td>Lengte:</td><td><input type="text" name="lengte" value="' . $lengte . '"></tr>');
                    print('<tr><td>Prijs:</td><td><input type="text" name="prijs" value="' . $prijs . '"></td><td></td><td>EAN:</td><td><input type="text" name="EAN" value="' . $EAN . '"></td></tr>');
                    print('<tr><td>Afbeelding:</td><td><input type="text" name="afbeelding" value="../administratie/img/"' . $afbeelding . '"></form>');
                    // upload form
                    print('<tr><table class="table">');
                    print('<form enctype="multipart/form-data" action"productbeheer.php" method="POST"');
                    print('<input type="hidden" name="MAX_FILE_SIZE" value="512000" />');
                    print('<tr><td><input name="uploadimg" type="file" /></td>');
                    print('<td><input type="submit" class="button" value"upload" /></td>');
                    print('</form>');
                    print('</table></tr>');
                    // einde upload form
                    print('<tr><td colspan=2><input form="toevoegen" type="submit" name="actie" class="button" value="' . $waarde . '"></tr>');
                    //print('</form>');
                    print('</table><br><br><form id="select"  action="" method="GET"></form>');

					?>
					<div class="header_administratie">Product zoeken</div>	
						<table class="table">
							<tr>
								<form id="zoeken" method="get" action="">
							</tr>
							<tr>
								<td>Zoeken : <input type="text" name="zoektext"	></td>
								<td>E.g productnaam,productnummer of omschrijving</td>
							</tr>
							<tr>
								<td><input form="zoeken" type="submit" class="button" name="zoeksubmit" value="Zoeken"></td>
							</tr>
						</form>
					</table>
					<?php
                    // afbeelding uploaden

                    print('</table><br><br><form id="select" action="" method="GET"></form>');


                  

                    // afbeelding uploaden naar de map img/
                    if (isset($_FILES['uploadimg'])) {
                        $uploaddir = 'img/';
                        $uploadfile = $uploaddir . basename($_FILES['uploadimg']['name']);

                        echo "<p>";
                        
                        // geeft een melding of de afbeelding is geupload of niet
                        if (move_uploaded_file($_FILES['uploadimg']['tmp_name'], $uploadfile)) {
                            echo "Afbeelding is geupload.\n";
                        } else {
                            echo "Uploaden van afbeelding is mislukt.";
                        }
                    }

                    // toont resultaten
                    $query = 'SELECT * FROM product WHERE 1=1 ';
					
					if(isset($_GET["zoektext"])){
						$query = search_query_generate($_GET["zoektext"], $query);
					}	
					
                    // perpage deel

                    if (!empty($_GET["perpage"])) {
                        $perpage = $_GET["perpage"];
                    } else {
                        $perpage = 20;
                    }
                    
                    // Limit functie voor maximaal aantal producten op 1 pagina
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

                  if (!empty($_GET["action"])) {
                    if ($_GET["action"] == "<") {
                        if ($pages != 0) {
                            $pages = $_GET["pages"] - $perpage;
                        }
                    }else {
                        if (!(($pages / $perpage) >= $amount)){
                            $pages = $_GET["pages"] + $perpage;
                        }else{
                            $pages = $perpage;
                        }
                    }
                }

                    $query = limit_query_generate($pages, $query, $perpage);
					
                    $result = mysqli_query($link, $query);
                    $row = mysqli_fetch_assoc($result);
                    ?>
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
                if (!mysqli_num_rows($resultcount) == 0) {
                    print(" Aantal resultaten: " . mysqli_num_rows($resultcount));
                }
                ?>
               
                    </div>
                    
                    
                    <?php
                    // Geeft alle producten die in de database staan weer op de pagina
                    print('<table class="table_administratie"><tr><th>productnr</th><th>productnaam</th><th>categorie</th><th>subcategorie</th><th>merk</th><th>EAN</th><th>voorraad</th><th>prijs</th><th>afbeelding</th><th>Verwijderen</th><th>Aanpassen</th></tr>');
                    while ($row) {
                        print('<tr><td>' . $row["productnr"] . '</td>'
                                . '<td>' . $row["productnaam"] . '</td>'
                                . '<td>' . $row["categorie"] . '</td>'
                                . '<td>' . $row["subcategorie"] . '</td>'
                                . '<td>' . $row["merk"] . '</td>'
                                . '<td>' . $row["EAN"] . '</td>'
                                . '<td>' . $row["voorraad"] . '</td>'
                                . '<td>&euro; ' . prijsformat($row["prijs"]) . '</td>');
                        if ($row['afbeelding'] == "../administratie/img/") {
                            print('<td><img class="small" src="../plaatjes/logo.png" width="100"></td>');
                        } else {
                            print('<td><img class="small" src="' . $row["afbeelding"] . '" ></td>');
                        }
                        print('<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="productnr" value="' . $row["productnr"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete();"></form></td>'
                                . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="productnr" value="' . $row["productnr"] . '"><input type="submit" name="actie" value="Aanpassen"></form></td></tr>');
                        $row = mysqli_fetch_assoc($result);
                    }
                    print("</table>");
                    ?>


                    <?php if(($pages / $perpage +1) !=1){ 
			print('<input type="submit" name="action" value="<" form="select">');
		} ?>	
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
			<?php if(($pages / $perpage +1) != round($amount)){ 	
				print('<input type="submit" name="action" value=">" form="select">');
			 } ?>

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
