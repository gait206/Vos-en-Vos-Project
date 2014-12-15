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
							if ($actie == "Verwijderen") {
								$klantnr = $_POST["klantnr"];
                                           mysqli_query($link, 'DELETE FROM klant WHERE klantnr = '.$klantnr.'; '); 
										   print(mysqli_error($link));	
										   mysqli_query($link, 'DELETE FROM gebruiker WHERE klantnr = '.$klantnr.';');
										   print(mysqli_error($link));	
							}

							if ($actie == "Bijwerken") {
								$klantnr = $_POST["klantnr"];
								$email = $_POST["email"];
								$voornaam =  $_POST["voornaam"];
								$achternaam = $_POST["achternaam"];
								$telnummer = $_POST["telnummer"];	
								$mobnummer = $_POST["mobnummer"];
								$bedrijf = $_POST["bedrijf"];
								$kvknummer = $_POST["kvknummer"];
								$btwnummer = $_POST["btwnummer"];
								$adres = $_POST['adres'];
								$plaats = $_POST['plaats'];
								$postcode = $_POST['postcode'];
								$level = $_POST['level'];
								mysqli_query($link, 'UPDATE klant k join gebruiker g on g.klantnr=k.klantnr and g.klantnr = "'.$klantnr.'" SET email = "'.$email.'",  voornaam = "'.$voornaam.'" , achternaam ="'.$achternaam.'" , telnummer="'.$telnummer.'"'
								.'  , mobnummer="'.$mobnummer.'" , bedrijfsnaam ="'.$bedrijf.'" , kvknummer ="'.$kvknummer.'" , btwnummer ="'.$btwnummer.'" , adres="'.$adres.'"'
								.' , plaats="'.$plaats.'" , postcode="'.$postcode.'"  , level ="'.$level.'";');
								print(mysqli_error($link));
							}
						}
						if (!empty($_POST["actie"])) {
							$actie = $_POST["actie"];
						} else {
							$actie = "";
						}
						
						if ($actie == "Aanpassen") {
							
							$result = mysqli_query($link, 'SELECT * FROM klant k, gebruiker g WHERE k.klantnr = g.klantnr and g.klantnr ="' . $_POST["klantnr"] . '";');
							$row = mysqli_fetch_assoc($result);
							$email = $row["email"];
							$voornaam =  $row["voornaam"];
							$achternaam = $row["achternaam"];
							$telnummer = $row["telnummer"];	
							$mobnummer = $row["mobnummer"];
							$bedrijf = $row["bedrijfsnaam"];
							$kvknummer = $row["kvknummer"];
							$btwnummer = $row["btwnummer"];
							$adres = $row['adres'];
							$plaats = $row['plaats'];
							$postcode = $row['postcode'];
							$level = $row['level'];
							
							$waarde = "Bijwerken";
							print('<table><form id="toevoegen" method="POST" action="">'
							. '<tr><td>Email:</td><td><input type="text" name="email" value="'. $email .'"></td></tr>'
							. '<tr><td>Voornaam:</td><td><input type="text" name="voornaam" value="'. $voornaam	.'	"></td></tr>'
							. '<tr><td>achternaam:</td><td><input type="text" name="achternaam" value="'. $achternaam.'"></td></tr>'
							. '<tr><td>Telefoon nummer:</td><td><input type="text" name="telnummer" value="'.$telnummer.'"></td></tr>'
							. '<tr><td>Mobiel nummer:</td><td><input type="text" name="mobnummer" value="'.$mobnummer.'"></td></tr>'
							. '<tr><td>Bedrijf:</td><td><input type="text" name="bedrijf" value="'.$bedrijf.'"></td></tr>'	
							. '<tr><td>KVKnummer:</td><td><input type="text" name="kvknummer" value="'.$kvknummer.'"></td></tr>'	
							. '<tr><td>BTWnummer:</td><td><input type="text" name="btwnummer" value="'.$btwnummer.'"></td></tr>'
							. '<tr><td>Adres:</td><td><input type="text" name="adres" value="'. $adres.'"><input type="text" name="plaats" value="'. $plaats.'"><input type="text" name="postcode" value="'. $postcode.'"></td></tr>'
							. '<tr><td>Level:</td><td><input type="text" name="level" value="'.$level.'"></td></tr>'	
							. '<input type="hidden" name="klantnr" value="' . $row["klantnr"] . '">'
							. '</form></table>'
							.  '<input form="toevoegen" type="submit" name="actie" class="button" value="' .$waarde. '">'
						);
						} 
						if($actie == "bekijken"){
							$klantnr = $_POST["klantnr"];
							$result = mysqli_query($link, 'SELECT * FROM klant k, gebruiker g WHERE k.klantnr = g.klantnr and g.klantnr ="' . $_POST["klantnr"] . '";');
							$row = mysqli_fetch_assoc($result);
							$email = $row["email"];
							$voornaam =  $row["voornaam"];
							$achternaam = $row["achternaam"];
							$telnummer = $row["telnummer"];	
							$mobnummer = $row["mobnummer"];
							$bedrijf = $row["bedrijfsnaam"];
							$kvknummer = $row["kvknummer"];
							$btwnummer = $row["btwnummer"];
							$adres = $row['adres'];
							$plaats = $row['plaats'];
							$postcode = $row['postcode'];
							$level = $row['level'];
							
							print('<table>'
							. '<tr><td>Email:</td><td>'. $email.'</td></tr>'
							. '<tr><td>Voornaam:</td><td>'. $voornaam.'</td></tr>'
							. '<tr><td>achternaam:</td><td>'. $achternaam.'</td></tr>'
							. '<tr><td>Telefoon nummer:</td><td>'.$telnummer.'</td></tr>'
							. '<tr><td>Mobiel nummer:</td><td>'.$mobnummer.'</td></tr>'
							. '<tr><td>Bedrijf:</td><td>'.$bedrijf.'</td></tr>'	
							. '<tr><td>KVKnummer:</td><td>'.$kvknummer.'</td></tr>'	
							. '<tr><td>BTWnummer:</td><td>'.$btwnummer.'</td></tr>'
							. '<tr><td>Adres:</td><td>'. $adres ." " . $plaats ." ". $postcode.'</td></tr>'
							. '<tr><td>Level:</td><td>'.$level.'</td></tr>'	
							. '</table>'
							);
						}
											
						$result = mysqli_query($link, 'SELECT * FROM gebruiker g, klant k where g.klantnr =k.klantnr');
						$row = mysqli_fetch_assoc($result);
						
						print('<table class="table_administratie"><tr><th>Email</th><th>Naam</th><th>telefoon</th><th>bedrijfsnaam</th><th>plaats</th><th>level</th><th>Verwijderen</th><th>Aanpassen</th><th>Bekijken</th></tr>');
						while ($row) {
							print('<tr><td>' . $row["email"] . '</td>'
									. '<td>' . $row["voornaam"]." ". $row["achternaam"] . '</td>'
									. '<td>' . $row["telnummer"] . '</td>'
									. '<td>' . $row["bedrijfsnaam"] . '</td>'
									. '<td>' . $row["plaats"] . '</td>'
									. '<td>' . $row["level"] . '</td>'
									. '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="klantnr" value="' . $row["klantnr"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete()"></form></td>'
									. '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="klantnr" value="' . $row["klantnr"] . '"><input type="submit" name="actie" value="Aanpassen">	</form></td>'
									. '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="klantnr" value="' . $row["klantnr"] . '"><input type="submit" name="actie" value="bekijken"></form></td></tr>');
							$row = mysqli_fetch_assoc($result);
						}
						print("</table>");
						?>

					</div>

					
				</div>

				<div class="footer">
				<?php
				include "../footer.php";
				?>
				</div>

			</div>
			
		</body>
	</html>
	<?php
				mysqli_close($link);
				?>
