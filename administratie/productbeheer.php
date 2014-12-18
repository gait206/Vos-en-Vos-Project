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
			define('THIS_PAGE', 'Productbeheer');
			include('../menu.php');
			?>

            <div class="content">
                <script>
                    function checkDelete(){
                        return confirm("Weet u zeker dat u dit product wilt verwijderen?");
                    }
                </script>
                <div class="body" id="main_content">
                    <?php
                    restrictedPage("Admin", $link);

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
                            
                            if(!empty($productnr)){
                            mysqli_query($link, "INSERT INTO product(productnr,productnaam,categorie,subcategorie,merk,omschrijving,voorraad,prijs,afbeelding,EAN,kleur,materiaal,hoogte,breedte,lengte,fabrikant,inhoud,verpakking,certificaten) VALUES('".$productnr."',' ".$productnaam."', '".$categorie."', '".$subcategorie."', '".$merk."','". $omschrijving."', '".$voorraad."', '".$prijs."', '".$afbeelding."', '".$EAN."', '".$kleur."', '".$materiaal."', '".$hoogte."', '".$breedte."', '".$lengte."', '".$fabrikant."', '".$inhoud."', '".$verpakking."', '".$certificaten."');");
                            print(mysqli_error($link));
                            }
                        }
                        
                        if ($actie == "Verwijderen") {
                                    $productnr = $_POST["productnr"];
                                           mysqli_query($link, 'DELETE FROM product WHERE productnr = "' . $productnr . '";'); 
                        }

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

                            mysqli_query($link, 'UPDATE product SET productnr = "'.$productnr.'", productnaam = "' . $productnaam . '", categorie = "' . $categorie . '", subcategorie = "' . $subcategorie . '", merk = "' . $merk . '", omschrijving = "' . $omschrijving . '", voorraad = "' . $voorraad . '", prijs = "' . $prijs . '", afbeelding = "' . $afbeelding . '", EAN = "' . $EAN . '", kleur = "' . $kleur . '", materiaal = "' . $materiaal . '", hoogte = "' . $hoogte . '", breedte = "' . $breedte . '", lengte = "' . $lengte . '", fabrikant = "' . $fabrikant . '", inhoud = "' . $inhoud . '", verpakking = "' . $verpakking . '", certificaten = "' . $certificaten . '"  WHERE productnaam = "' . $productnaam . '";');
                            print(mysqli_error($link));
                        }
                    }

                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                    } else {
                        $actie = "";
                    }



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
                    print('<div class="header_administratie">Product toevoegen</div>');
                    print('<table class="table">');
                    print('<form id="toevoegen" method="post" action=""');
                    print('<tr><td>Productnr:</td><td><input type="text" name="productnr" value="' .$productnr. '"><td></td>      <td colspan=2>Extra informatie</td>    <td>Fabrikant:</td><td><input type="text" name="fabrikant" value="' .$fabrikant. '"></tr>');
                    print('<tr><td>Productnaam:</td><td><input type="text" name="productnaam" value="' .$productnaam. '"><td></td>      <td>Kleur:</td><td><input type="text" name="kleur" value="' .$kleur. '">     <td>Inhoud:</td><td><input type="text" name="inhoud" value="' .$inhoud. '"></tr>');
                    print('<tr><td>Categorie:</td><td><input type="text" name="categorie" value="' .$categorie. '"><td></td>      <td>Materiaal:</td><td><input type="text" name="materiaal" value="' .$materiaal. '">     <td>Verpakking:</td><td><input type="text" name="verpakking" value="' .$verpakking. '"></tr>');
                    print('<tr><td>Subcategorie:</td><td><input type="text" name="subcategorie" value="' .$subcategorie. '"><td></td>      <td>Hoogte:</td><td><input type="text" name="hoogte" value="' .$hoogte. '">     <td>Certificaten:</td><td><input type="text" name="certificaten" value="' .$certificaten. '"></tr>');
                    print('<tr><td>Merk:</td><td><input type="text" name="merk" value="' .$merk. '"><td></td>      <td>Breedte:</td><td><input type="text" name="breedte" value="' .$breedte. '"><td>Omschrijving:</td><td rowspan=6><textarea rows=6 cols=50 name="omschrijving">'.$omschrijving.'</textarea></tr>');
                    print('<tr><td>Voorraad:</td><td><input type="text" name="voorraad" value="' .$voorraad. '"><td></td>      <td>Lengte:</td><td><input type="text" name="lengte" value="' .$lengte. '"></tr>');
                    print('<tr><td>Prijs:</td><td><input type="text" name="prijs" value="' .$prijs. '"></td><td></td><td>EAN:</td><td><input type="text" name="EAN" value="' .$EAN. '"></td></tr>');
                    print('<tr><td>Afbeelding:</td><td><input type="text" name="afbeelding" value="../administratie/img/"' .$afbeelding. '"></form>');
//                     upload form
                    print('<tr><table class="table">');
                    print('<form enctype="multipart/form-data" action"productbeheer.php" method="POST"');
                    print('<input type="hidden" name="MAX_FILE_SIZE" value="512000" />');
                    print('<tr><td><input name="uploadimg" type="file" /></td>');
                    print('<td><input type="submit" class="button" value"upload" /></td>');
                    print('</form>');
                    print('</table></tr>');
//                     einde upload form
                    print('<tr><td colspan=2><input form="toevoegen" type="submit" name="actie" class="button" value="' .$waarde. '"></tr>');
                    //print('</form>');
                    print('</table><br><br>');
                    
                    
                    // afbeelding uploaden
                    
                    
                    if(isset($_FILES['uploadimg'])){
                    $uploaddir = 'img/';
                    $uploadfile = $uploaddir . basename($_FILES['uploadimg']['name']);

                    echo "<p>";

                    if (move_uploaded_file($_FILES['uploadimg']['tmp_name'], $uploadfile)) {
                        echo "Afbeelding is geupload.\n";
                    } else {
                        echo "Uploaden van afbeelding is mislukt.";
                    }
                    }

                    //echo "</p>";
                    //echo '<pre>';
                    //echo 'Debug informatie:';
                    //print_r($_FILES);
                    //print "</pre>";
                    
                    // toont resultaten
                    $result = mysqli_query($link, 'SELECT * FROM product');
                    $row = mysqli_fetch_assoc($result);

                    print('<table class="table_administratie"><tr><th>productnr</th><th>productnaam</th><th>categorie</th><th>subcategorie</th><th>merk</th><th>EAN</th><th>voorraad</th><th>prijs</th><th>afbeelding</th><th>Verwijderen</th><th>Aanpassen</th></tr>');
                    while ($row) {
                        print('<tr><td>' . $row["productnr"] . '</td>'
                                . '<td>' . $row["productnaam"] . '</td>'
                                . '<td>' . $row["categorie"] . '</td>'
                                . '<td>' . $row["subcategorie"] . '</td>'
                                . '<td>' . $row["merk"] . '</td>'
                                . '<td>' . $row["EAN"] . '</td>'
                                . '<td>' . $row["voorraad"] . '</td>'
                                . '<td>' . number_format($row["prijs"], 2) . '</td>'
                                . '<td><img class="small" src="' . $row["afbeelding"] . '" ></td>'
                                . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="productnr" value="' . $row["productnr"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete()"></form></td>'
                                . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="productnr" value="' . $row["productnr"] . '"><input type="submit" name="actie" value="Aanpassen"></form></td></tr>');
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
