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
                            $merk = $_POST["merk"];
                            $omschrijving = $_POST["omschrijving"];
                            $voorraad = $_POST["voorraad"];
                            $prijs = $_POST["prijs"];
                            $afbeelding = $_POST["afbeelding"];
                            
                            if(!empty($productnr)){
                            mysqli_query($link, "INSERT INTO product(productnr,productnaam,categorie,merk,omschrijving,voorraad,prijs,afbeelding) VALUES('".$productnr."',' ".$productnaam."', '".$categorie."', '".$merk."','". $omschrijving."', '".$voorraad."', '".$prijs."', '".$afbeelding."');");
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
                            $merk = $_POST["merk"];
                            $omschrijving = $_POST["omschrijving"];
                            $voorraad = $_POST["voorraad"];
                            $prijs = $_POST["prijs"];
                            $afbeelding = $_POST["afbeelding"];

                            mysqli_query($link, 'UPDATE product SET productnr = "'.$productnr.'", productnaam = "' . $productnaam . '", categorie = "' . $categorie . '", merk = "' . $merk . '", omschrijving = "' . $omschrijving . '", voorraad = "' . $voorraad . '", prijs = "' . $prijs . '", afbeelding = "' . $afbeelding . '" WHERE productnaam = "' . $productnaam . '";');
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
                        $merk = $row["merk"];
                        $omschrijving = $row["omschrijving"];
                        $voorraad = $row["voorraad"];
                        $prijs = $row["prijs"];
                        $afbeelding = $row["afbeelding"];
                        $waarde = "Bijwerken";
                    } else {
                        $productnr = "";
                        $productnaam = "";
                        $categorie = "";
                        $merk = "";
                        $omschrijving = "";
                        $voorraad = "";
                        $prijs = "";
                        $afbeelding = "";
                        $waarde = "Toevoegen";
                    }
                    print('<div class="header_administratie">Product toevoegen</div>');
                    print('<table class="table">');
                    print('<form id="toevoegen" method="post" action=""');
                    print('<tr><td>Productnr:</td><td><input type="text" name="productnr" value="' .$productnr. '"></tr>');
                    print('<tr><td>Productnaam:</td><td><input type="text" name="productnaam" value="' .$productnaam. '"></tr>');
                    print('<tr><td>Categorie:</td><td><input type="text" name="categorie" value="' .$categorie. '"></tr>');
                    print('<tr><td>Merk:</td><td><input type="text" name="merk" value="' .$merk. '"></tr>');
                    print('<tr><td>Omschrijving:</td><td><input type="text" name="omschrijving" value="' .$omschrijving. '"></tr>');
                    print('<tr><td>Voorraad:</td><td><input type="text" name="voorraad" value="' .$voorraad. '"></tr>');
                    print('<tr><td>Prijs:</td><td><input type="text" name="prijs" value="' .$prijs. '"></tr>');
                    print('<tr><td>Afbeelding:</td><td><input type="text" name="afbeelding" value="img/"' .$afbeelding. '"></form>');
//                     upload form
                    print('<td><table class="table">');
                    print('<form enctype="multipart/form-data" action"productbeheer.php" method="POST"');
                    print('<input type="hidden" name="MAX_FILE_SIZE" value="512000" />');
                    print('<tr></tr><tr><td><input name="uploadimg" type="file" /></td>');
                    print('<td><input type="submit" class="button" value"upload" /></td>');
                    print('</form>');
                    print('</table></td></tr>');
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

                    print('<table class="table_administratie"><tr><th>productnr</th><th>productnaam</th><th>categorie</th><th>merk</th><th>omschrijving</th><th>voorraad</th><th>prijs</th><th>afbeelding</th><th>Verwijderen</th><th>Aanpassen</th></tr>');
                    while ($row) {
                        print('<tr><td>' . $row["productnr"] . '</td>'
                                . '<td>' . $row["productnaam"] . '</td>'
                                . '<td>' . $row["categorie"] . '</td>'
                                . '<td>' . $row["merk"] . '</td>'
                                . '<td>' . $row["omschrijving"] . '</td>'
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

            </div>

        </div>
        
    </body>
</html>
<?php
            mysqli_close($link);
            ?>
