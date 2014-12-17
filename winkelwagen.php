<?php
$cookiename = 'winkelmandje';
session_start();
include('functies.php');
$link = connectDB();
//deleteCookie($cookiename);
if (!existCookie($cookiename)) {
    addCookie($cookiename, array());
}  
if (!empty($_POST["actie"])) {
    if ($_POST["actie"] == "Verwijderen") {
        removeCookieLine($cookiename, $_POST["productnr"]);
        header('Location: winkelwagen.php');
    } else if ($_POST["actie"] == "toevoegen") {
		//print("1");
		if(array_key_exists($_POST["productnr"] ,getCookie($cookiename))){
			//print("2");
			modifyCookieLine($cookiename, $_POST["productnr"], getCookie($cookiename)[$_POST["productnr"]]+ 1);
			header('Location: winkelwagen.php');
		} else {
			//print("3");
			addCookieLine($cookiename, $_POST["productnr"], 1);
			header('Location: winkelwagen.php');
		}
	}
}
if (!empty($_POST["aanpassen"])) {
    if($_POST["aanpassen"] >= 0) {
        modifyCookieLine($cookiename, $_POST["productnr"], $_POST["aanpassen"]);
        header('Location: winkelwagen.php');
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/main.css">
       <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/winkelwagen.css">
		 <link rel="stylesheet" type="text/css" href="css/afrekenen.css">

    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <a href="index.php"><img class="logo" src="plaatjes/logo.png"></a>
                </div>
                <div class="login">
                    <?php
                    include('login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
            define('THIS_PAGE', 'winkelwagen');
            include('menu.php');
            ?>

            <div class="content">

                <div class="body" id="main_content">
                    <?php
                   
                    
                    
                    print('<table class="table_administratie">');
                    if(countItems(getCookie("winkelmandje"))>=1){
					print('<tr>'
					
                            . '<th style="text-align:center;">Verwijderen</th>'
							. '<th style="text-align:center;"><!--Afbeelding--></th>'
                            . '<th style="text-align:center;">Productnummer</th>'
                            . '<th style="text-align:left; padding-left:30px;" >Productnaam</th>'
							. '<th style="text-align:center;">Aantal</th>'
							. '<th style="text-align:center;">Prijs per stuk</th>'
                            . '<th style="text-align:center;">Subtotaal</th>'
							.'</tr><tr colspan=7 height="10"></tr>');
							}


                    // totaalBedragZonderBTW, totaalBedrag en totaalBTW instellen
                    $totaalBedragZonderBTW = 0;
                    $totaalBedrag = 0;
                    $totaalBTW = 0;
                    $cookie = getCookie("winkelmandje");
                    // $conn = connectDB();
                    // $stmt = $con->prepare("SELECT afbeelding, productnaam");
                    $count = 0;
					if(is_array($cookie) && count($cookie)> 0){
                    foreach ($cookie as $key => $value) {
                        $result = mysqli_query($link, 'SELECT * FROM product WHERE productnr="' . $key . '";');

                        // eerste rij ophalen ** DATABASE **
                        $row = mysqli_fetch_assoc($result);

						$afbeelding = $row["afbeelding"];
						$product_nummer = $row["productnr"];
                        $product_naam = $row["productnaam"];
                        $product_omschrijving = $row["omschrijving"];
                        $product_prijs = $row["prijs"];
                        // ER IS NOG GEEN BTW tabel
                        $btw = 21;
                        $product_btw = 1 + ($btw / 100);

                        // berekening totaal prijs van aantal producten
                        $totalePrijsZonderBTW = 0;
                        $totalePrijsZonderBTW = $product_prijs * $value;
                        $totalePrijs = 0;
                        $totalePrijs = $totalePrijsZonderBTW * $product_btw;
                        // berekening totaal bedragen
                        $totaalBedragZonderBTW = $totaalBedragZonderBTW + $totalePrijsZonderBTW;
                        $totaalBedrag = $totaalBedrag + $totalePrijs;
                        $totaalBTW = $totaalBTW + ($totalePrijs - $totalePrijsZonderBTW);


                        // printen waarden
                        print('<tr>'
								. '<form  action="" method="POST" >'
                                . '<td style="text-align:center;" width="10%"><input type="hidden" name="productnr" value="' . $row["productnr"] . '">'
                                . '<input type="image" src="/plaatjes/deleteicon.png" height="30" name="actie" value="Verwijderen" onClick="return checkDelete()"></form></td><td style="text-align:center;" width="20%">');
								if($afbeelding == ""){
				  				print('<img height="80" style="max-width:180px" src="./plaatjes/logo.png">');
				 				}
				  				else{
				  				print('<img height="80" style="max-width:180px" src='. $afbeelding .'>');
				  				}

								print('</td><td style="text-align: center;" width="10%">' . $product_nummer . '</td>'
                                . '<td style="text-align:left; padding-left:30px; color:#344d8e;" width="30%">' . $product_naam . '</td>'
                                . '<td style="text-align: center;" width="10%"><form class="table_administratie"  action="" method="POST" >'
                                . '<input  type="number" class="textbox" name="aanpassen" value="' . $value . '" onchange=this.form.submit();> </td>'
                                 . '<input  type="hidden" name="productnr" value="' . $row["productnr"] . '"></form>'
                                . '<td style="text-align: center;" width="10%">&euro; ' . prijsformat($product_prijs) . '</td>'
                                . '<td style="text-align: center; width="10%"">&euro; ' . prijsformat($totalePrijsZonderBTW) . '</td></tr>
									<tr><td colspan=7><img height=5px width=100% src="./plaatjes/line.png"></tr>');
                        $count++;
					}
					} else{
						print('</table><p style="margin-left:20px; color:orange;">U heeft nog geen artikelen in het winkelmandje</p>');
					}
					if(countItems(getCookie("winkelmandje"))>=1){
                    print('</table>');
                    print('<div class="afrekenen_totaal"><ul><li class="afrekenen_totaal_text"><h3>Bedrag Zonder BTW:</h3></li><li><h3> &euro; ' . prijsformat($totaalBedragZonderBTW) . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h3>Totaal BTW: </h3></li><li><h3>&euro; ' . prijsformat($totaalBTW) . '</h3></li></ul><br>');
                    print('<ul><li class="afrekenen_totaal_text"><h2>Totaal: </h2></li><li><h2>&euro; ' . prijsformat($totaalBedrag) . '</h2></li></ul>');
					}
                    ?>
                    <?php
					if(countItems(getCookie("winkelmandje"))>=1){
                    print('<form class="winkelwagen_button" action="betaling/inloggen.php" method="POST">
                        <input type="submit" name="Betalen" value="Doorgaan"> 
                    </form>');
					}
					?>
                </div>
            </div>

            <div class="footer">
			<?php
			include "footer.php";
			?>
            </div>

        </div>
    </body>
</html>
<?php
mysqli_close($link);
?>