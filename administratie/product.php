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
						
						
						//productnaam
						print('<div class="productnaam">');
                        print('<h2>'.$productnaam.'</h2></div>');
						
						//BESTELBLOK
						print('<div class="bestelblok">
						<p class="pronr">productnummer '. $productnr .'</p>
						<p class="prijs">&euro; '. prijsformat($prijs) .'</p>
						<p class="prijsklein">&euro; '. prijsber($prijs) .' incl 21% BTW</p>');
							
						//voorraad
                        if ($voorraad >20){
                            print('<p class="opvoorraad"> Op voorraad</p><br>');
                        } 
						if ($voorraad >0 && $voorraad <=20){
							print('<p class="beperktopvoorraad"> Beperkt op voorraad</p><br>');
						}
						if ($voorraad ==0){
                            print('<p class="nietopvoorraad"> Niet op voorraad</p><br>');
                        }
						$value = 1;
						print(    '<form action="../winkelwagen.php" method="POST">'
                                . ' <input  type="hidden" name="productnr" value="' . $productnr . '">'
                                . ' <input type="submit" class="inwinkelwagen" name="actie" value="in winkelmandje">'
                                . '</form>');
                        
                        
						print('</div>');
						
						//afbeelding
						print('<br><div class="afbeelding">');
						if ($afbeelding == "../administratie/img/"){
							print('<img class="afbeelding" src="../plaatjes/logo.png" ><br></div><br>');
						}
						else{
							print('<img width="200px" src="'. $afbeelding .'" ><br></div><br>');
						}
						
						//omschrijving
                        print('<h3>Omschrijving:</h3><div class="omschrijving">'. $omschrijving .'</div><br><br>');
                        
						//tabel extra gegevens
						
						print('<h3>Extra informatie:</h3><table class="tabel" border=0>
						<tr class="test"><td class="test">productnummer</td><td>'.$productnr.'</td></tr>
						<tr><td>EAN</td><td>'.$EAN.'</td></tr>
						<tr><td>categorie</td><td>'.$categorie.'</td></tr>
						<tr><td>subcategorie</td><td>'.$subcategorie.'</td></tr>
						<tr><td>kleur</td><td>'.$kleur.'</td></tr>
						<tr><td>inhoud</td><td>'.$inhoud.'</td></tr>
						<tr><td>maat (LBH)</td><td>'.$lengte. ' x '.$breedte. ' x '.$hoogte.' cm</td></tr>
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