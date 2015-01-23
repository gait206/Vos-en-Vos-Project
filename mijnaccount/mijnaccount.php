<?php
session_start();
include('../functies.php');
$link = connectDB();

include('../login/loginscherm.php');
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
                    include('../login/loginscherm2.php');
                    ?>
                </div>
            </div>

            <?php
			if(userLevel(getKlantnr($link), $link) == "Admin") {
			define('THIS_PAGE', 'Admin');
			}
			if(userLevel(getKlantnr($link), $link) == "Gebruiker"){
			define('THIS_PAGE', 'Mijn gegevens');
			}
			
			
			include('../menu.php');
			?>
            <div class="content">
                
                

                <div class="body">
                    <?php
                        $link = connectDB();
                    if (mysqli_connect_error($link)) {
                        print(mysqli_connect_error($link));
                    }
			if(!validToken($link)){
                            header('Location: ../index.php');
				die();
			} else {
                    
                    $klantnr = getKlantnr($link);
                    
                    // als het wachtwoord gewijzigd wordt via het formulier word deze gewijzigd in de database
                    if (isset($_POST["opslaan2"])) {
                        $wachtwoord3 = $_POST['wachtwoord3'];
                        $result2 = mysqli_query($link, "SELECT email FROM gebruiker WHERE klantnr = '".$klantnr."';");
                        $row2 = mysqli_fetch_assoc($result2);
                        
                        // controleert of het wachtwoord gelijk is met het wachtwoord dat herhaalt word
                        // controleert of het wachtwoord voldoet aan de eisen:
                        // minimaal 6 tekens, 1 hoofdletter, 1 kleine letter en 1 special teken
                        if ($_POST['wachtwoord'] != $_POST['wachtwoord2']) {
                                $error_wachtwoord = "<img width=15 height=15 src=\"fout.png\"> De wachtwoorden komen niet overeen<br>";
                            } elseif (strlen($_POST['wachtwoord']) < 6){
                                $error_wachtwoord = "<img width=15 height=15 src=\"fout.png\"> Het wachtwoord moet minimaal 6 tekens bevatten";
                            } elseif (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $_POST['wachtwoord'])){
                                $error_wachtwoord = "<img width=15 height=15 src=\"fout.png\"> Wachtwoord moet minimaal 1 cijfer, 1 letter, 1 hoofdletter en 1 speciaal teken bevatten<br>";
                            } else {
                                $error_wachtwoord = '';
                            }
                        
                            $wachtwoord = $_POST["wachtwoord"];
                            $wachtwoord2 = $_POST['wachtwoord2'];
                        
                            // controleert of het huidige wachtwoord klopt voordat het nieuwe wachtwoord kan worden opgeslagen
                        if (verifyPassword($row2['email'], $wachtwoord3, $link)){
                            
                            // controleert of er geen velden leeg zijn gelaten en of de nieuwe wachtwoorden overeenkomen
                            if (!empty($wachtwoord) && ($_POST['wachtwoord'] == $_POST['wachtwoord2']) && $error_wachtwoord == '') {
                                
                                // zorgt voor de encryptie van het wachtwoord
                                $wachtwoord3 = encryptPassword($wachtwoord);
                                
                                // update het wachtwoord in de database
                            $stmt = mysqli_prepare($link, "UPDATE gebruiker SET wachtwoord = ? WHERE klantnr = '".$klantnr."';");    
                                mysqli_stmt_bind_param($stmt, 's', $wachtwoord3);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
                            
                                print(mysqli_stmt_error($link));
                            
                                // Verwijst door naar de pagina dat de wijziging is gelukt
                            header('Location: gegevensgewijzigd.php');
                        } 
                        } else {
                            // Error weergeven als het huidige wachtwoord niet klopt
                            $error_wachtwoord3 = "<img width=15 height=15 src=\"fout.png\"> Het huidige wachtwoord klopt niet<br>";
                        }
                    } else {
                            // zorgt dat er geen foutmelding te zien zijn als de pagina wordt geopend
                            // en dat de velden leeg zijn als de pagina wordt geopend
                            $error_wachtwoord = '';
                            $error_wachtwoord3 = '';
                            $wachtwoord = '';
                            $wachtwoord2 = '';
                            $wachtwoord3 = '';
                    }
                    
                    if (isset($_POST["opslaan"])) {
                            
                            // Foutcontrole contactgegevens
                            // controleert of de voornaam niet leeg is en alleen uit letters bestaat ( een - mag )
                            if (empty($_POST['voornaam'])){
                                $error_voornaam = "<img width=15 height=15 src=\"fout.png\"> Er is geen voornaam ingevoerd<br>";
                                } elseif (preg_match("/^- [A-z]+$/", $_POST['voornaam'])){
                                    $error_voornaam = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij voornaam<br>";
                            } else {
                                $error_voornaam = '';
                            }
                            
                            // controleert of de achternaam niet leeg is en alleen uit letters bestaat ( een - mag )
                            if (empty($_POST['achternaam'])){
                                $error_achternaam = "<img width=15 height=15 src=\"fout.png\"> Er is geen achternaam ingevoerd<br>";
                                } elseif (preg_match("/^- [A-z]+$/", $_POST['achternaam'])){
                                    $error_achternaam = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij achternaam<br>";
                            } else {
                                $error_achternaam = '';
                            }
                            
                            // controleert of het input veld niet leeg is en of het telefoonnumer alleen uit cijfers bestaat
                            if (empty($_POST['telnummer'])){
                                $error_telnummer = "<img width=15 height=15 src=\"fout.png\"> Er is geen telefoonnummer ingevoerd<br>";   
                            } elseif (!preg_match("/^[0-9]+$/", $_POST['telnummer'])){
                                    $error_telnummer = "<img width=15 height=15 src=\"fout.png\"> Het telefoonnummer mag alleen getallen bevatten<br>";
                            } else {
                                $error_telnummer = '';
                            }
                            
                            // controleert of het mobiele nummer alleen uit cijfers bestaat
                            if (empty($_POST['mobnummer'])){
                                $error_mobnummer = "<img width=15 height=15 src=\"fout.png\"> Er is geen mobiele nummer ingevoerd<br>";   
                            } elseif (!preg_match("/^[0-9]+$/", $_POST['mobnummer'])){
                                    $error_mobnummer = "<img width=15 height=15 src=\"fout.png\"> Het mobiele nummer mag alleen 10 getallen bevatten<br>";
                            } else {
                                $error_mobnummer = '';
                            }
                            // Foutcontrole bij bedrijfsgegevens
                            // controleert of de bedrijfsnaam niet leeg is en alleen uit letters bestaat
                            if (empty($_POST['bedrijfsnaam'])){
                                $error_bedrijfsnaam = "<img width=15 height=15 src=\"fout.png\"> Er is geen bedrijfsnaam ingevoerd<br>";
                            } elseif (preg_match("/^- [A-z]+$/", $_POST['bedrijfsnaam'])){
                                $error_bedrijfsnaam = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij bedrijfsnaam<br>";
                            } else {
                                $error_bedrijfsnaam = '';
                            }
                            // controleert of het adres niet leeg is en alleen uit letters en cijfers bestaat
                            if (empty($_POST['adres'])){
                                $error_adres= "<img width=15 height=15 src=\"fout.png\"> Er is geen adres ingevoerd<br>";
                            } elseif (preg_match("/^[A-z0-9]+$/", $_POST['adres'])){
                                $error_adres = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij adres<br>";
                            } else {
                                $error_adres = '';
                            }    
                            // controleert of de postcode niet leeg is en of het een geldig postcode is dmv Postcodecheck functie
                            if (empty($_POST['postcode'])){
                                $error_postcode = "<img width=15 height=15 src=\"fout.png\"> Er is geen postcode ingevoerd<br>";
                            } elseif (!PostcodeCheck($_POST['postcode'])){
                                $error_postcode = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij postcode<br>";
                            } else {
                                $error_postcode = '';
                            }    
                            // controleert of de plaats niet leeg is en alleen bestaat uit letters
                            if (empty($_POST['plaats'])){
                                $error_plaats = "<img width=15 height=15 src=\"fout.png\"> Er is geen plaats ingevoerd<br>";
                            } elseif (!preg_match("/^[A-Za-z]+$/", $_POST['plaats'])){
                                $error_plaats = "<img width=15 height=15 src=\"fout.png\"> Geen geldige invoer bij plaats<br>";
                            } else {
                                $error_plaats = '';
                            }
                            // controleert of btw nummer niet leeg is en het een geldig btw nummer is dmv de checkbtw functie
                            if (empty($_POST['btwnummer'])){
                                $error_btwnummer = "<img width=15 height=15 src=\"fout.png\"> Geen btwnummer ingevoerd<br>";
                            } elseif (!checkBTW($_POST['btwnummer'])){
                                $error_btwnummer = "<img width=15 height=15 src=\"fout.png\"> Geen geldig btwnummer ingevoerd<br>";
                            } else {
                                $error_btwnummer = '';
                            }
                            // controleert of het kvknummer niet leeg is en alleen bestaat uit 8 cijfers
                            if (empty($_POST['kvknummer'])){
                                $error_kvknummer = "<img width=15 height=15 src=\"fout.png\"> Geen kvknummer ingevoerd<br>";
                            } elseif (!preg_match("/^[0-9]{8}$/", $_POST['kvknummer'])) {
                                $error_kvknummer = "<img width=15 height=15 src=\"fout.png\"> Geen geldig kvknummer ingevoerd<br>";
                            } else {
                                $error_kvknummer = '';
                            }
                        
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
                            
                            // voert de query alleen uit als deze gegevens ingevuld zijn
                            if (!empty($voornaam) && !empty($achternaam) && !empty($telnummer) && !empty($bedrijfsnaam) && !empty($adres) && !empty($postcode) && !empty($plaats) && !empty($btwnummer) && !empty($kvknummer)){
                            
                            $stmt = mysqli_prepare($link, 'UPDATE klant SET voornaam = ?, achternaam = ?, telnummer = ?,  mobnummer = ?, bedrijfsnaam = ?, adres = ?, postcode = ?, plaats = ?, btwnummer = ?, kvknummer = ? WHERE klantnr = "'.$klantnr.'";');
							mysqli_stmt_bind_param($stmt, 'sssssssssi', $voornaam, $achternaam, $telnummer, $mobnummer, $bedrijfsnaam, $adres, $postcode, $plaats, $btwnummer, $kvknummer);
							mysqli_stmt_execute($stmt);
							mysqli_stmt_close($stmt);
                                // als de gegevens met succes zijn gewijzigd wordt er doorverwezen naar gegevensgewijzigd.php                        
                                header('Location: gegevensgewijzigd.php');
                            }
                            
                        } else {
                            $error_voornaam = '';
                            $error_achternaam = '';
                            $error_telnummer = '';
                            $error_mobnummer = '';
                            $error_bedrijfsnaam = '';
                            $error_adres = '';
                            $error_postcode = '';
                            $error_plaats = '';
                            $error_btwnummer = '';
                            $error_kvknummer = '';
                            
                            // zoekt in de database naar het klantnummer en haalt de klantgegevens uit de database
                            $result = mysqli_query($link, 'SELECT * FROM klant WHERE klantnr = "'.$klantnr.'";');
                            $row = mysqli_fetch_assoc($result);
                            
                            
                            $voornaam = $row['voornaam'];
                            $achternaam = $row['achternaam'];
                            $telnummer = $row['telnummer'];
                            $mobnummer = $row['mobnummer'];
                            //bedrijfsgegevens
                            $bedrijfsnaam = $row['bedrijfsnaam'];
                            $adres = $row['adres'];
                            $postcode = $row['postcode'];
                            $plaats = $row['plaats'];
                            $kvknummer = $row['kvknummer'];
                            $btwnummer = $row['btwnummer'];
                    }
                    
                    // formulier voor het aanpassen van gegevens of wachtwoord
                    print('<div class="header_administratie">Mijn gegevens</div>');
                    print('<table class="table">');
                    print('<form id="registreren" method="post" action=""');
                    // Contactpersoon
                    print('<tr><td><p class="p">Contactpersoon<p></td></tr>');
                    print('<tr><td>Voornaam:</td><td><input class="input" type="text" name="voornaam" value="' .$voornaam. '"><td class="foutmelding">'.$error_voornaam.'</td></tr>');
                    print('<tr><td>Achternaam:</td><td><input class="input" type="text" name="achternaam" value="' .$achternaam. '"><td class="foutmelding">'.$error_achternaam.'</td></tr>');
                    print('<tr><td>Telefoonnummer:</td><td><input class="input" type="text" name="telnummer" value="' .$telnummer. '"><td class="foutmelding">'.$error_telnummer.'</td></tr>');
                    print('<tr><td>Mobielnummer:</td><td><input class="input" type="text" name="mobnummer" value="' .$mobnummer. '"><td class="foutmelding">'.$error_mobnummer.'</td></tr>');
                    // Bedrijfsgegevens
                    print('<tr><td><p class="p">Bedrijfsgegevens<p></td></tr>');
                    print('<tr><td>Bedrijfsnaam:</td><td><input class="input" type="text" name="bedrijfsnaam" value="' .$bedrijfsnaam. '"><td class="foutmelding">'.$error_bedrijfsnaam.'</td></tr>');
                    print('<tr><td>Adres:</td><td><input class="input" type="text" name="adres" value="' .$adres. '"><td class="foutmelding">'.$error_adres.'</td></tr>');
                    print('<tr><td>Postcode:</td><td><input class="input" type="text" name="postcode" value="' .$postcode. '"><td class="foutmelding">'.$error_postcode.'</td></tr>');
                    print('<tr><td>Plaats:</td><td><input class="input" type="text" name="plaats" value="' .$plaats. '"><td class="foutmelding">'.$error_plaats.'</td></tr>');
                    print('<tr><td>KvK-nummer:</td><td><input class="input" type="text" name="kvknummer" value="' .$kvknummer. '"><td class="foutmelding">'.$error_kvknummer.'</td></tr>');
                    print('<tr><td>BTW-nummer:</td><td><input class="input" type="text" name="btwnummer" value="' .$btwnummer. '"><td class="foutmelding">'.$error_btwnummer.'</td></tr>');
                    print('<tr><td></td><td><input class="button" type="submit" name="opslaan" value="opslaan"></td>');
                    print('</form>');
                    // Inloggegevens
                    print('<form action="" method="POST"><tr><td><p class="p">Inloggegevens<p></td></tr>');
                    print('<tr><td>Huidig wachtwoord:</td><td><input class="input" type="password" name="wachtwoord3" value="' .$wachtwoord3. '"><td class="foutmelding">'.$error_wachtwoord3.'</td></tr>');
                    print('<tr><td>Nieuw wachtwoord:</td><td><input class="input" type="password" name="wachtwoord" value="' .$wachtwoord. '"><td class="foutmelding">'.$error_wachtwoord.'</td></tr>');
                    print('<tr><td>Herhaal wachtwoord:</td><td><input class="input" type="password" name="wachtwoord2" value="' .$wachtwoord2. '"></tr>');
                    print('<tr><td></td><td><input type="submit" name="opslaan2" class="button" value="Wachtwoord wijzigen"></td></tr>');
                    print('</form>');
                    print('</table>');
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
