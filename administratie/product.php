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
                    if (isset($_POST["productnr"])) {
                        //connectie maken en qeury versturen
                        $productnr = $_POST["productnr"];
                        if (mysqli_connect_error($link)) {
                            print(mysqli_connect_error($link));
                        }
                        //product gegevens ophalen
                        $stmt = mysqli_prepare($link, 'SELECT productnaam,merk,categorie,omschrijving,afbeelding,prijs, voorraad FROM product where productnr = ?');
                        mysqli_stmt_bind_param($stmt, "i", $productnr);
                        mysqli_execute($stmt);

                        mysqli_stmt_bind_result($stmt, $productnaam, $merk, $categorie, $omschrijving, $afbeelding, $prijs, $voorraad);
                        mysqli_stmt_fetch($stmt);
                       
                     
                        
                        //product weergeven
                        print("<h2>".$productnaam. "</h2><br><p>");
                        //print("<img>");
                        if($voorraad >0){
                            print("Beschikbaarheid: Op voorraad");
                        } else{
                            print("Beschikbaarheid: Niet op voorraad");
                        }
                        print("<br>". "<h3>Omschrijving</h3>". $omschrijving );
                        
                        
                        
                        print("</p>");
                    } else{
                        //foutmelding als geen productnr is gegeven
                        print("geen product geselecteerd");
                    }
                    ?>
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
    
    </body>
</html>
