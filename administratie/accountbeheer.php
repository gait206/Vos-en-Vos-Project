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
								$email = $_POST["email"];
                                           mysqli_query($link, 'DELETE FROM klant WHERE email = "' . $email . '";DELETE FROM gebruiker WHERE email = "' . $email . '";'); 
										   print(mysqli_error($link));	
							}

							if ($actie == "Bijwerken") {
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
								mysqli_query($link, 'UPDATE klant k join gebruiker g on g.email=k.email and k.email = "'.$email.'" SET k.email = "'.$email.'", g.email = "'.$email.'", voornaam = "'.$voornaam.'" , achternaam ="'.$achternaam.'" , telnummer="'.$telnummer.'"'
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
							$email = $_POST["email"];
							$result = mysqli_query($link, 'SELECT * FROM klant k, gebruiker g WHERE k.email = g.email and g.email ="' . $email . '";');
							$row = mysqli_fetch_assoc($result);
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
							. '</form></table>'
							.  '<input form="toevoegen" type="submit" name="actie" class="button" value="' .$waarde. '">'
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
									. '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="email" value="' . $row["email"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete()"></form></td>'
									. '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="email" value="' . $row["email"] . '"><input type="submit" name="actie" value="Aanpassen">	</form></td>'
									. '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="email" value="' . $row["email"] . '"><input type="submit" name="actie" value="bekijken"></form></td></tr>');
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
