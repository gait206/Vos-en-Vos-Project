
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/afrekenen.css">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="../plaatjes/logo.png">
                </div>
                <div class="login">
                    <form>
                        <table>
                            <tr><td></td></tr>
                            <tr><td>Gebruikersnaam:</td><td><input class="gebruikersnaam"type="text" value="naam"><br></td></tr>
                            <tr><td>Wachtwoord:</td><td><input class="wachtwoord" type="text" value="wachtwoord"></td></tr>
                            <tr><td><input type="submit" value="login"></td></tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="menu">
                <ul class="menu">
                    <li class="menu"><a class="ajax-link" href="userstory6_1.php">Home</a></li>
                    <li class="menu"><a href="#">Papier</a></li>
                    <li class="menu"><a href="#">Dispencers</a></li>
                    <li class="menu"><a href="#">Reinigingsmiddelen</a></li>
                    <li class="menu"><a href="#">Schoonmaakmateriaal</a></li>
                </ul>

            </div>

            <div class="content">

                <div class="navigator">
                    <div class="zoekbalk">

                        <input class="zoekinput" type="text" value="Zoek">
                        <input class="zoeksubmit" type="submit" value="Zoek">

                    </div>

                    <div class="navigatie">

                    </div>
                </div>

                <div class="body" id="main_content">

                    <?php
                    // User Storie 6

                    include '../functies.php';

                    print('<table class="afrekenen_tabel">');

                    // verbinding maken ** DATABASE **
                    $host = "localhost";
                    $user = "root";
                    $password = "usbw";
                    $database = "vvtissue";
                    $port = 3307;
                    $link = mysqli_connect($host, $user, $password, $database, $port);

                    // table printen
                    print('<tr><th>Product ID</th>'
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

                    // cookies ophalen ** WEGHALEN **
                    
                    $array = array(9 => 16, 11 => 10);
                    addCookie("winkelmandje", $array);
                    
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
                            $totaalBedragZonderBTW = number_format($totaalBedragZonderBTW + $totalePrijsZonderBTW, 2);
                            $totaalBedrag = number_format($totaalBedrag + $totalePrijs, 2);
                            $totaalBTW = number_format($totaalBTW + ($totalePrijs - $totalePrijsZonderBTW),2);


                            // printen waarden
                            print('<tr><td>' . $key . '</td>'
                                    . '<td>' . $product_naam . '</td>'
                                    . '<td>' . $product_omschrijving . '</td>'
                                    . '<td>' . $product_prijs . '</td>'
                                    . '<td>' . $btw . '%</td>'
                                    . '<td>' . $value . '</td>'
                                    . '<td>' . number_format($totalePrijsZonderBTW,2) . '</td></tr>');
                            $count++;
                        }
                    }
                    

                    // printen Totalen en balken
                    print('</table>');
                    print('<div class="afrekenen_totaal"><ul><li class="afrekenen_totaal_text"><h3>Bedrag Zonder BTW:</h3></li><li><h3>' . $totaalBedragZonderBTW . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h3>Totaal BTW: </h3></li><li><h3>' . $totaalBTW . '</h3></li></ul>');
                    print('<ul><li class="afrekenen_totaal_text"><h2>Totaal: </h2></li><li><h2>' . $totaalBedrag . '</h2></li></ul>');
                    ?>
                    <form class="afrekenen_form"><input type="submit" name="ideal" value="Afrekenen met IDeal"></form></div>
                </div>

                <div class="banner">

                </div>
            </div>

            <div class="footer">

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
