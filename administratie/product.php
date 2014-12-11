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


                <?php
                define('THIS_PAGE', 'product');
                include '../menu.php';
                ?>


            <div class="content">
                </div>

                <div class="body" id="main_content">
                    <?php
                    if (isset($_GET["productnr"])) {
                        //connectie maken en qeury versturen
                        $productnr = $_GET["productnr"];
                        if (mysqli_connect_error($link)) {
                            print(mysqli_connect_error($link));
                        }
                        //product gegevens ophalen
                        $stmt = mysqli_prepare($link, 'SELECT * FROM product where productnr = ?');
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
    </body>
</html>
<?php
mysqli_close($link);
?>