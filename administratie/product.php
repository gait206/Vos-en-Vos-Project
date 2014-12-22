<?php
session_start();
include('../functies.php');
$link = connectDB();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/product.css">
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
            define('THIS_PAGE', 'product');
            include '../menu.php';
            ?>
          


            <div class="content">
               

                <div class="body" id="main_content">
                
                    <?php
                    if (isset($_GET["productnr"])) {
                        //connectie maken en qeury versturen
                        $productnr = $_GET["productnr"];
                        if (mysqli_connect_error($link)) {
                            print(mysqli_connect_error($link));
                        }
                        //product gegevens ophalen
                        $stmt = mysqli_prepare($link, 'SELECT productnr, productnaam, EAN, merk, categorie, subcategorie, omschrijving, prijs, voorraad, afbeelding, kleur, hoogte, breedte, lengte, fabrikant, verpakking, certificaten, inhoud, materiaal FROM product where productnr = ?');
                        mysqli_stmt_bind_param($stmt, "i", $productnr);
                        mysqli_execute($stmt);

                        mysqli_stmt_bind_result($stmt, $productnr, $productnaam, $EAN, $merk, $categorie, $subcategorie, $omschrijving, $prijs, $voorraad, $afbeelding, $kleur, $hoogte, $breedte, $lengte, $fabrikant, $verpakking, $certificaten, $inhoud, $materiaal);
                        mysqli_stmt_fetch($stmt);
                       
                     
                        
                        //PRODUCT WEERGEVEN
						
						?>
                        <div class="productnaam">
                        <?php
						//productnaam
                        print("<h2>".$productnaam."</h2>");
						?>
                        </div>
                        <div class="afbeelding">
                        <?php
						//afbeelding
						if ($afbeelding == "../administratie/img/"){
							print('<img class="afbeelding" src="../plaatjes/logo.png" ><br>');
						}
						else{
							print('<img width="200px" src="'. $afbeelding .'" ><br>');
						}
						?>
                        </div>
                        
                        <?php
						//voorraad
                        if ($voorraad >20){
                            print('<div class="opvoorraad"> Beschikbaarheid: Op voorraad');
                        } 
						if ($voorraad >0 && $voorraad <=20){
							print('<div class="beperktopvoorraad"> Beschikbaarheid: Beperkt op voorraad');
						}
						if ($voorraad ==0){
                            print('<div class="nietopvoorraad"> Beschikbaarheid: Niet op voorraad');
                        }
						?>
                        </div>
                        <?php
						//omschrijving
                        print('<br>'. '<h3>Omschrijving:</h3><div class="omschrijving">'. $omschrijving .'</div><br><br>');
                        
						//tabel extra gegevens
						
						print('<h3>Extra informatie:</h3><table class="tabel" border=0>
						<tr class="test"><td class="test">productnummer</td><td>'.$productnr.'</td></tr>
						<tr><td>EAN</td><td>'.$EAN.'</td></tr>
						<tr><td>categorie</td><td>'.$categorie.'</td></tr>
						<tr><td>subcategorie</td><td>'.$subcategorie.'</td></tr>
						<tr><td>kleur</td><td>'.$kleur.'</td></tr>
						<tr><td>inhoud</td><td>'.$inhoud.'</td></tr>
						<tr><td>maat (cm)</td><td>L:'.$lengte. ' B:'.$breedte. ' H:'.$hoogte.'</td></tr>
						<tr><td>materiaal</td><td>'.$materiaal.'</td></tr>
						<tr><td>verpakking</td><td>'.$verpakking.'</td></tr>
						<tr><td>merk</td><td>'.$merk.'</td></tr>
						<tr><td>fabrikant</td><td>'.$fabrikant.'</td></tr>
						<tr><td>certificaten</td><td>'.$certificaten.'</td></tr>
						</table>');
						?>
                        </div>
                        
                        
                        <?php
                    } else{
                        //foutmelding als geen productnr is gegeven
                        print("geen product geselecteerd");
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