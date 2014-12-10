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
                    <img class="logo" src="../plaatjes/logo.png">
                </div>
                <div class="login">
                    <?php
                    include('../login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
			define('THIS_PAGE', 'Productbeheer');
			include('../menu.php');
			?>

            <div class="content">
                <script>
                    function checkDelete(){
                        return confirm("Weet u zeker dat u deze gebruiker wilt verwijderen?");
                    }
                </script>
                <div class="body" id="main_content">
                    <?php
                    restrictedPage("Admin", $link);
					
					if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                        if ($actie == "Toevoegen") {
						
						
						
						
							if(!empty($productnr)){
					   
							}
						}
                        
						if ($actie == "Verwijderen") {
						
						}

                        if ($actie == "Bijwerken") {
					    
						}
                    }
					if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                    } else {
                        $actie = "";
                    }
					
					if ($actie == "Aanpassen") {
						$email = $_POST["email"];
                        $result = mysqli_query($link, 'SELECT * FROM klant k, gebruiker g WHERE k.email = g.email and email ="' . $email . '";');
                        $row = mysqli_fetch_assoc($result);
						
						
						
						$waarde = "Bijwerken";
						print('<table><form id="toevoegen" method="post" action="">'
						. '<tr><td>Email:</td><td><input type="text" name="email" value="			"></td></tr>'
						. '<tr><td>Naam:</td><td><input type="text" name="naam" value="			"></td></tr>'
						. '<tr><td>Telefoon nummer:</td><td><input type="text" name="telnummer" value="			"></td></tr>'
						. '<tr><td>Bedrijf:</td><td><input type="text" name="bedrijf" value="			"></td></tr>'	
						. '<tr><td>KVKnummer:</td><td><input type="text" name="kvknummer" value="			"></td></tr>'	
						. '<tr><td>BTWnummer:</td><td><input type="text" name="btwnummer" value="			"></td></tr>'
						. '<tr><td>Adres:</td><td><input type="text" name="adres" value="			"></td></tr>'
						. '<tr><td>Level:</td><td><input type="text" name="level" value="			"></td></tr>'	
						. '<input form="toevoegen" type="submit" name="actie" class="button" value="' .$waarde. '">'						
						. '</form></table>'
					);
					} else {
					
					}
										
					$result = mysqli_query($link, 'SELECT * FROM gebruiker g, klant k where g.email =k.email');
                    $row = mysqli_fetch_assoc($result);
                    
					print('<table class="table_administratie"><tr><th>Email</th><th>Naam</th><th>telefoon</th><th>bedrijfsnaam</th><th>plaats</th><th>level</th><th>Verwijderen</th><th>Aanpassen</th><th>Bekijken</th></tr>');
                    while ($row) {
                        print('<tr><td>' . $row["email"] . '</td>'
                                . '<td>' . $row["voornaam"]." ". $row["achternaam"] . '</td>'
                                . '<td>' . $row["telnummer"] . '</td>'
                                . '<td>' . $row["bedrijfsnaam"] . '</td>'
                                . '<td>' . $row["plaats"] . '</td>'
								. '<td>' . $row["level"] . '</td>'
                                . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="productnr" value="' . $row["email"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete()"></form></td>'
                                . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="productnr" value="' . $row["email"] . '"><input type="submit" name="actie" value="Aanpassen">	</form></td>'
								. '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="productnr" value="' . $row["email"] . '"><input type="submit" name="actie" value="bekijken"></form></td></tr>');
                        $row = mysqli_fetch_assoc($result);
                    }
                    print("</table>");
                    ?>

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
