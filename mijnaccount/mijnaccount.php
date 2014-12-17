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
			define('THIS_PAGE', 'Home');
			include('../menu.php');
			?>
            <div class="mijnaccount">
                <?php
                    print('<div class="header_administratie">Mijn account</div>');
                ?>  
            </div>
            <div class="content">
                
                

                <div class="body">
                    <?php
                        $result = mysqli_query($link, 'SELECT * FROM klant k, gebruiker g WHERE k.klantnr=g.klantnr AND k.klantnr ="' . getKlantnr($link) . '";');
                        $row = mysqli_fetch_assoc($result);
                            //Contactpersoon
                            $voornaam = $row["voornaam"];
                            $achternaam = $row["achternaam"];
                            $telnummer = $row["telnummer"];
                            $mobnummer = $row["mobnummer"];
                            //bedrijfsgegevens
                            $bedrijfsnaam = $row["bedrijfsnaam"];
                            $adres = $row["adres"];
                            $postcode = $row["postcode"];
                            $plaats = $row["plaats"];
                            $kvknummer = $row["kvknummer"];
                            $btwnummer = $row["btwnummer"]; 
                            //inloggegevens
                            $email = $row["email"];                        
                    
                    print('<div class="header_administratie">Mijn gegevens</div>');
                    print('<table class="table">');
                    // Contactpersoon
                    print('<tr><td><p class="p">Gegevens ontactpersoon<p></td></tr>');
                    print('<tr><td>Voornaam:</td><td>'.$voornaam.'</td></tr>');
                    print('<tr><td>Achternaam:</td><td>'.$achternaam.'</td></tr>');
                    print('<tr><td>Telefoonnummer:</td><td>'.$telnummer.'</td></tr>');
                    print('<tr><td>Mobielnummer:</td><td>'.$mobnummer.'</td></tr>');
                    // Bedrijfsgegevens
                    print('<tr><td><p class="p">Bedrijfsgegevens<p></td></tr>');
                    print('<tr><td>Bedrijfsnaam:</td><td>'.$bedrijfsnaam.'</td></tr>');
                    print('<tr><td>Adres:</td><td>'.$adres.'</td></tr>');
                    print('<tr><td>Postcode:</td><td>'.$postcode.'</td></tr>');
                    print('<tr><td>Plaats:</td><td>'.$plaats.'</td></tr>');
                    print('<tr><td>KvK-nummer:</td><td>'.$kvknummer.'</td></tr>');
                    print('<tr><td>BTW-nummer:</td><td>'.$btwnummer.'</td></tr>');
                    // Inloggegevens
                    print('<tr><td><p class="p">Inloggegevens<p></td></tr>');
                    print('<tr><td>Emailadres:</td><td>'.$email.'</td></tr>');
                    print('</table>');
                    
                    ?>
   
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
