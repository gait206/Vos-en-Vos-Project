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
                            
                            header('Location: registratievoltooid.php');
                            }
                            // Foutcontrole bij de contactgegevens
                                 
                            if (empty($_POST['voornaam'])){
                                $error_voornaam = "<img width=15 height=15 src=\"fout.png\"> Er is geen voornaam ingevoerd<br>";
                                } elseif (!preg_match("/^[A-z]+$/", $_POST['voornaam'])){
                                    $error_voornaam = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij voornaam<br>";
                            } else {
                                $error_voornaam = '';
                            }
                                
                            if (empty($_POST['achternaam'])){
                                $error_achternaam = "<img width=15 height=15 src=\"fout.png\"> Er is geen achternaam ingevoerd<br>";
                                } elseif (!preg_match("/^[A-z]+$/", $_POST['achternaam'])){
                                    $error_achternaam = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij achternaam<br>";
                            } else {
                                $error_achternaam = '';
                            }
                            
                            if (empty($_POST['telnummer'])){
                                $error_telnummer = "<img width=15 height=15 src=\"fout.png\"> Er is geen telefoonnummer ingevoerd<br>";   
                            } elseif (!preg_match("/^[0-9]+$/", $_POST['telnummer'])){
                                    $error_telnummer = "<img width=15 height=15 src=\"fout.png\"> Het telefoonnummer mag alleen getallen bevatten<br>";
                            } else {
                                $error_telnummer = '';
                            }
                            // Foutcontrole bij bedrijfsgegevens
                            
                            if (empty($_POST['bedrijfsnaam'])){
                                $error_bedrijfsnaam = "<img width=15 height=15 src=\"fout.png\"> Er is geen bedrijfsnaam ingevoerd<br>";
                            } elseif (preg_match("/^[A-z]+$/", $_POST['bedrijfsnaam'])){
                                $error_bedrijfsnaam = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij bedrijfsnaam<br>";
                            } else {
                                $error_bedrijfsnaam = '';
                            }
                            
                            if (empty($_POST['adres'])){
                                $error_adres= "<img width=15 height=15 src=\"fout.png\"> Er is geen adres ingevoerd<br>";
                            } elseif (preg_match("/^[A-z0-9]+$/", $_POST['adres'])){
                                $error_adres = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij adres<br>";
                            } else {
                                $error_adres = '';
                            }    
                            
                            if (empty($_POST['postcode'])){
                                $error_postcode = "<img width=15 height=15 src=\"fout.png\"> Er is geen postcode ingevoerd<br>";
                            } elseif (!PostcodeCheck($_POST['postcode'])){
                                $error_postcode = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij postcode<br>";
                            } else {
                                $error_postcode = '';
                            }    
                            
                            if (empty($_POST['plaats'])){
                                $error_plaats = "<img width=15 height=15 src=\"fout.png\"> Er is geen plaats ingevoerd<br>";
                            } elseif (preg_match("/^[A-z]+$/", $_POST['plaats'])){
                                $error_plaats = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij plaats<br>";
                            } else {
                                $error_plaats = '';
                            }
                            
                            if (empty($_POST['btwnummer'])){
                                $error_btwnummer = "<img width=15 height=15 src=\"fout.png\"> Geen btwnummer ingevoerd<br>";
                            } else {
                                $error_btwnummer = '';
                            }
                            
                            if (empty($_POST['kvknummer'])){
                                $error_kvknummer = "<img width=15 height=15 src=\"fout.png\"> Geen kvknummer ingevoerd<br>";
                            } else {
                                $error_kvknummer = '';
                            }
                            
                            // Foutcontrole bij inloggegevens
                            if (!validate_email($_POST['email'])){
                                $error_email = "<img width=15 height=15 src=\"fout.png\"> Geen geldig emailadres ingevoerd<br>";
                            } elseif (empty($_POST['email'])){
                                $error_email = "<img width=15 height=15 src=\"fout.png\"> Er is geen email ingevoerd<br>";
                            } else {
                                $error_email = '';
                            }
                            if ($_POST['wachtwoord'] != $_POST['wachtwoord2']) {
                                $error_wachtwoord = "<img width=15 height=15 src=\"fout.png\"> De wachtwoord komen niet overeen!<br>";
                            } elseif (!preg_match('~^(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d)(?=[A-Za-z0-9]*[^A-Za-z0-9])\S{6,}$~', $_POST['wachtwoord'])){
                                $error_wachtwoord = "<img width=15 height=15 src=\"fout.png\"> Het wachtwoord moet minimaal 6 tekens bevatten met 1 hoofdletter, 1 kleine letter, 1 getal en 1 speciaal teken";
                            } else {
                                $error_wachtwoord = '';
                            }
                            
                        } else {
                            $error_voornaam = '';
                            $error_achternaam = '';
                            $error_telnummer = '';
                            $error_bedrijfsnaam = '';
                            $error_adres = '';
                            $error_postcode = '';
                            $error_plaats = '';
                            $error_btwnummer = '';
                            $error_kvknummer = '';
                            $error_email = '';
                            $error_wachtwoord = '';
                            
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
                    print('<tr><td>Voornaam:</td><td><input class="input" type="text" name="voornaam" value="' .$voornaam. '"><td class="foutmelding">'.$error_voornaam.'</td></tr>');
                    print('<tr><td>Achternaam:</td><td><input class="input" type="text" name="achternaam" value="' .$achternaam. '"><td class="foutmelding">'.$error_achternaam.'</td></tr>');
                    print('<tr><td>telefoonnummer:</td><td><input class="input" type="text" name="telnummer" value="' .$telnummer. '"><td class="foutmelding">'.$error_telnummer.'</td></tr>');
                    print('<tr><td>Mobielnummer:</td><td><input class="input" type="text" name="mobnummer" value="' .$mobnummer. '"></tr>');
                    // Bedrijfsgegevens
                    print('<tr><td><p class="p">Bedrijfsgegevens<p></td></tr>');
                    print('<tr><td>Bedrijfsnaam:</td><td><input class="input" type="text" name="bedrijfsnaam" value="' .$bedrijfsnaam. '"><td class="foutmelding">'.$error_bedrijfsnaam.'</td></tr>');
                    print('<tr><td>Adres:</td><td><input class="input" type="text" name="adres" value="' .$adres. '"><td class="foutmelding">'.$error_adres.'</td></tr>');
                    print('<tr><td>Postcode:</td><td><input class="input" type="text" name="postcode" value="' .$postcode. '"><td class="foutmelding">'.$error_postcode.'</td></tr>');
                    print('<tr><td>Plaats:</td><td><input class="input" type="text" name="plaats" value="' .$plaats. '"><td class="foutmelding">'.$error_plaats.'</td></tr>');
                    print('<tr><td>KvK-nummer:</td><td><input class="input" type="text" name="kvknummer" value="' .$kvknummer. '"><td class="foutmelding">'.$error_kvknummer.'</td></tr>');
                    print('<tr><td>BTW-nummer:</td><td><input class="input" type="text" name="btwnummer" value="' .$btwnummer. '"><td class="foutmelding">'.$error_btwnummer.'</td></tr>');
                    // Inloggegevens
                    print('<tr><td><p class="p">Inloggegevens<p></td></tr>');
                    print('<tr><td>Emailadres:</td><td><input class="input" type="text" name="email" value="' .$email. '"><td class="foutmelding">'.$error_email.'</td></tr>');
                    print('<tr><td>Wachtwoord:</td><td><input class="input" type="password" name="wachtwoord" value="' .$wachtwoord. '"><td class="foutmelding">'.$error_wachtwoord.'</td></tr>');
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
