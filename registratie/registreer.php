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
        <link rel="stylesheet" type="text/css" href="../css/registreren.css">
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
			define('THIS_PAGE', 'Home');
			include('../menu.php');
			?>

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
                    $link = connectDB();
                    if (mysqli_connect_error($link)) {
                        print(mysqli_connect_error($link));
                    }
                    // nieuwe klant registreren
                    if (isset($_POST["registreer"])) {
                                                
                            //Contactpersoon
                            $voornaam = $_POST["voornaam"];
                            $achternaam = $_POST["achternaam"];
                            $telnummer = $_POST["telnummer"];
                            $mobnummer = $_POST["mobnummer"];
                            //bedrijfsgegevens
                            $bedrijfsnaam = $_POST["bedrijfsnaam"];
                            $adres = $_POST["adres"];
                            $postcode = $_POST["postcode"];
                            $plaats = $_POST["plaats"];
                            $kvknummer = $_POST["kvknummer"];
                            $btwnummer = $_POST["btwnummer"]; 
                            //inloggegevens
                            $email = $_POST["email"];
                            $wachtwoord = $_POST["wachtwoord"];
                            $wachtwoord2 = $_POST['wachtwoord2'];
                            
                            if (($_POST['wachtwoord'] == $_POST['wachtwoord2']) && !empty($email)) {
                                
                                $wachtwoord3 = encryptPassword($wachtwoord);
                            
                           
                            mysqli_query($link, "INSERT INTO gebruiker(email,wachtwoord) VALUES('".$email."', '".$wachtwoord3."');");    
                            mysqli_query($link, "INSERT INTO klant(voornaam,achternaam,telnummer,mobnummer,bedrijfsnaam,adres,postcode,plaats,kvknummer,btwnummer,email) "
                            . "VALUES('".$voornaam."', '".$achternaam."', '.$telnummer.','. $mobnummer.', '".$bedrijfsnaam."', '".$adres."', '".$postcode."', '".$plaats."', '.$kvknummer.', '.$btwnummer.', '".$email."');"); 
                            
                            print(mysqli_error($link));
                            
                            } else {
                                if ($_POST['wachtwoord'] != $_POST['wachtwoord2']) {
                                print("De wachtwoord komen niet overeen!");
                            }
                            }
                    } else {
                            $voornaam = '';
                            $achternaam = '';
                            $telnummer = '';
                            $mobnummer = '';
                            //bedrijfsgegevens
                            $bedrijfsnaam = '';
                            $adres = '';
                            $postcode = '';
                            $plaats = '';
                            $kvknummer = '';
                            $btwnummer = '';
                            //inloggegevens
                            $email = '';
                            $wachtwoord = '';
                            $wachtwoord2 = '';
                    }
                       
                    print('<div class="header_administratie">Registreren</div>');
                    print('<table class="table">');
                    print('<form id="registreren" method="post" action="registreer.php"');
                    // Contactpersoon
                    print('<tr><td><p class="p">Contactpersoon<p></td></tr>');
                    print('<tr><td>Voornaam:</td><td><input class="input" type="text" name="voornaam" value="' .$voornaam. '"></tr>');
                    print('<tr><td>Achternaam:</td><td><input class="input" type="text" name="achternaam" value="' .$achternaam. '"></tr>');
                    print('<tr><td>telefoonnummer:</td><td><input class="input" type="text" name="telnummer" value="' .$telnummer. '"></tr>');
                    print('<tr><td>Mobielnummer:</td><td><input class="input" type="text" name="mobnummer" value="' .$mobnummer. '"></tr>');
                    // Bedrijfsgegevens
                    print('<tr><td><p class="p">Bedrijfsgegevens<p></td></tr>');
                    print('<tr><td>Bedrijfsnaam:</td><td><input class="input" type="text" name="bedrijfsnaam" value="' .$bedrijfsnaam. '"></tr>');
                    print('<tr><td>Adres:</td><td><input class="input" type="text" name="adres" value="' .$adres. '"></tr>');
                    print('<tr><td>Postcode:</td><td><input class="input" type="text" name="postcode" value="' .$postcode. '"></tr>');
                    print('<tr><td>Plaats:</td><td><input class="input" type="text" name="plaats" value="' .$plaats. '"></tr>');
                    print('<tr><td>KvK-nummer:</td><td><input class="input" type="text" name="kvknummer" value="' .$kvknummer. '"></tr>');
                    print('<tr><td>BTW-nummer:</td><td><input class="input" type="text" name="btwnummer" value="' .$btwnummer. '"></tr>');
                    // Inloggegevens
                    print('<tr><td><p class="p">Inloggegevens<p></td></tr>');
                    print('<tr><td>Emailadres:</td><td><input class="input" type="text" name="email" value="' .$email. '"></tr>');
                    print('<tr><td>Wachtwoord:</td><td><input class="input" type="password" name="wachtwoord" value="' .$wachtwoord. '"></tr>');
                    print('<tr><td>Herhaal wachtwoord:</td><td><input class="input" type="password" name="wachtwoord2" value="' .$wachtwoord2. '"></tr>');
                    print('<tr><td colspan=2><input type="submit" name="registreer" class="button" value="Registreren"></td></tr>');
                    print('</form>');
                    print('</table>');
                    
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
