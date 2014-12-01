<?php
session_start();
include('functies.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/admin.css">
        <link rel="stylesheet" type="text/css" href="../css/afrekenen.css">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="plaatjes/logo.png">
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

            <div class="content" id="main_content">

                <div class="body" id="main_content"> <?php
                    $cookiename = 'winkelmandje';
                    if (!existCookie($cookiename)) {
                        addCookie($cookiename, array());
                    }
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
                    $cookie = getCookie("winkelmandje");
                   // $conn = connectDB();
                   // $stmt = $con->prepare("SELECT afbeelding, productnaam");
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
                        $totaalBedragZonderBTW = number_format($totaalBedragZonderBTW + $totalePrijsZonderBTW, 2);
                        $totaalBedrag = number_format($totaalBedrag + $totalePrijs, 2);
                        $totaalBTW = number_format($totaalBTW + ($totalePrijs - $totalePrijsZonderBTW), 2);


                        // printen waarden
                        print('<tr>'
                                . '<td>' . $product_naam . '</td>'
                                . '<td>' . $product_omschrijving . '</td>'
                                . '<td>' . $product_prijs . '</td>'
                                . '<td>' . $btw . '%</td>'
                                . '<td>' . $value . '</td>'
                                . '<td>' . number_format($totalePrijsZonderBTW, 2) . '</td></tr>');
                        $count++;
                    }
                    print('</table>');
                    print('<div class="afrekenen_totaal"><ul><li class="afrekenen_totaal_text"><h3>Bedrag Zonder BTW:</h3></li><li><h3>' . $totaalBedragZonderBTW . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h3>Totaal BTW: </h3></li><li><h3>' . $totaalBTW . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h2>Totaal: </h2></li><li><h2>' . $totaalBedrag . '</h2></li></ul>');
                    ?>
                </div>
            </div>

            <div class="footer">

            </div>

        </div>


    </body>
</html>
