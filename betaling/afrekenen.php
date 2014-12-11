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
        <link rel="stylesheet" type="text/css" href="../css/afrekenen.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
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

            <!--<div class="menu">-->
                <?php
			define('THIS_PAGE', 'Afrekenen');
			include('../menu.php');
			?>

            <!--</div>-->

            <div class="content">

                <div class="body" id="main_content">

                    <?php
                    // User Storie 6

                    print('<table class="afrekenen_tabel">');



                    // table printen
                    print('<tr>'
                            . '<th>Product Naam</th>'
                            . '<th>Product Omschrijving</th>'
                            . '<th>Prijs</th>'
                            . '<th>BTW</th>'
                            . '<th>Aantal</th>'
                            . '<th>Totaal Bedrag</th></tr>');

                    // totaalBedragZonderBTW, totaalBedrag en totaalBTW instellen
                    $totaalBedragZonderBTW = 0;
                    $totaalBedrag = 0;
                    $totaalBTW = 0;
                    
                    // Test Waarden
                    if (existCookie("winkelmandje")) {
                        $cookie = getCookie("winkelmandje");

                        // rijen count voor opmaak
                        $count = 0;

                        // inhoud printen
                        foreach ($cookie as $key => $value) {
                            $result = mysqli_query($link, 'SELECT * FROM product WHERE productnr="' . $key . '";');

                            // eerste rij ophalen ** DATABASE **
                            $row = mysqli_fetch_assoc($result);

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
                                    . '<td>' . $product_naam . '</td>'
                                    . '<td>' . $product_omschrijving . '</td>'
                                    . '<td> &euro; ' . prijsformat($product_prijs) . '</td>'
                                    . '<td>' . $btw . '%</td>'
                                    . '<td>' . $value . '</td>'
                                    . '<td> &euro; ' . prijsformat($totalePrijsZonderBTW) . '</td></tr>');
                            $count++;
                        }
                    }
                    

                    // printen Totalen en balken
                    print('</table>');
                    print('<div class="afrekenen_totaal"><ul><li class="afrekenen_totaal_text"&euro;><h3>Bedrag Zonder BTW:</h3></li><li><h3> &euro; ' . prijsformat($totaalBedragZonderBTW) . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h3>Totaal BTW: </h3></li><li><h3>&euro; ' . prijsformat($totaalBTW) . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h2>Totaal: </h2></li><li><h2>&euro; ' . prijsformat($totaalBedrag) . '</h2></li></ul>');
                    ?>
                    <br>
                    <form class="afrekenen_form" method="POST" action="verzenden.php"><input type="submit" name="actie" value="Doorgaan"></form></div>
                    <br>
                    <form method="POST" action="../winkelwagen.php"><input class="afrekenen_knop_left" type="submit" name="terug" value="Terug naar winkelwagen"></form>
                </div>

            </div>

            <div class="footer">
            <?php
			include "../footer.php";
			?>
            </div>

        </div>
        <?php
        // put your code here
        ?>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script>
            $(function () {
                $("a.ajax-link").on("click", function (e) {
                    e.preventDefault();
                    $("#main_content").load(this.href);
                });
            });
        </script>
    </body>
</html>
<?php
            mysqli_close($link);
            ?>
