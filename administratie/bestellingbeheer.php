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
            define('THIS_PAGE', 'Admin');
            include('../menu.php');
            ?>

            <div class="content">
                <script>
                    function checkDelete() {
                        return confirm("Weet u zeker dat u deze bestelling wilt verwijderen?");
                    }
                </script>
                <div class="body" id="main_content">
                    <?php
                    restrictedPage("Admin", $link);

                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                        if ($actie == "Verwijderen") {
                            $bestelnr = $_POST["bestelnr"];
                            mysqli_query($link, 'DELETE FROM anderadres WHERE bestelnr = "' . $bestelnr . '";');
                            mysqli_query($link, 'DELETE FROM bestelregel WHERE bestelnr = "' . $bestelnr . '";');
                            mysqli_query($link, 'DELETE FROM bestelling WHERE bestelnr = "' . $bestelnr . '";');
                            print(mysqli_error($link));
                        }

                        if ($actie == "Bijwerken") {
                            $bestelnr = $_POST["bestelnr"];
                            $status = $_POST["status"];
                            $bezorgdatum = $_POST["bezorgdatum"];
                            mysqli_query($link, 'UPDATE bestelling SET status = "' . $status . '", bezorgdatum = "' . $bezorgdatum . '" WHERE bestelnr = "' . $bestelnr . '";');
                            print(mysqli_error($link));
                        }
                    }
                    if (!empty($_POST["actie"])) {
                        $actie = $_POST["actie"];
                    } else {
                        $actie = "";
                    }

                    if ($actie == "Aanpassen") {
                        $bestelnr = $_POST["bestelnr"];
                        $result = mysqli_query($link, 'SELECT * FROM bestelling WHERE bestelnr ="' . $bestelnr . '";');
                        $row = mysqli_fetch_assoc($result);
                        $status = $row["status"];
                        $bezorgdatum = $row["bezorgdatum"];

                        if (function_exists('date_default_timezone_set')) {
                            date_default_timezone_set('Europe/Amsterdam');
                        }

                        $waarde = "Bijwerken";
                        print('<table><form id="toevoegen" method="POST" action="">'
                                . '<tr><td>Bestelnummer:</td><td>' . $bestelnr . '</td></tr>'
                                . '<tr><td>Bezorgdatum:</td><td><input type="date" min="' . date("Y-m-d", time()) . '" name="bezorgdatum" value="' . $bezorgdatum . '"></td></tr>'
                                . '<tr><td>Status:</td><td>'
                                . '<select name="status"><option value="In behandeling">In behandeling</option>'
                                . '<option value="Verzonden">Verzonden</option>'
                                . '<option value="Geannuleerd">Geannuleerd</option>'
                                . '<option value="Afgehandeld">Afgehandeld</option></select></td></tr>'
                                . '<input type="hidden" name="bestelnr" value="' . $bestelnr . '">'
                                . '<tr><td></td><td></td><td><input type="submit" name="actie" class="button" value="' . $waarde . '"></td></tr>'
                                . '</table>'
                                . '</form>'
                        );
                    } else {
                        
                    }
					
					?>
					<div class="header_administratie">Bestelling zoeken</div>	
						<table class="table">
							<tr>
								<form id="zoeken" method="get" action="">
							</tr>
							<tr>
								<td>Zoeken : <input type="text" name="zoektext"	></td>
								<td>E.g klantnummer,bestelnummer of transactie referentie</td>
							</tr>
							<tr>
								<td><input form="zoeken" type="submit" class="button" name="zoeksubmit" value="Zoeken"></td>
							</tr>
						</form>
					</table>
					<?php

                    // selecteert alle bestelling die betaald zijn en niet geannuleerd zijn
					$query ='SELECT * FROM bestelling ';
					
					if(isset($_GET["zoektext"])){
						
						$query = search_query_generate_bestelling($_GET["zoektext"], $query);
					}	
				
					if (!empty($_GET["perpage"])) {
                        $perpage = $_GET["perpage"];
                    } else {
                        $perpage = 20;
                    }

                    $resultcount = mysqli_query($link, $query);
                    $amount = amount_per_page($resultcount, $perpage);

                    if (!empty($_GET["pages"])) {
                        if ($_GET["pages"] == $_GET["ref"]) {
                            $pages = 0;
                        } else {
                            $pages = $_GET["pages"];
                        }
                    } else {
                        $pages = 0;
                    }

                  if (!empty($_GET["action"])) {
                    if ($_GET["action"] == "<") {
                        if ($pages != 0) {
                            $pages = $_GET["pages"] - $perpage;
                        }
                    }else {
                        if (!(($pages / $perpage) >= $amount)){
                            $pages = $_GET["pages"] + $perpage;
                        }else{
                            $pages = $perpage;
                        }
                    }
                }

                    $query = limit_query_generate($pages, $query, $perpage);
                    $result = mysqli_query($link, $query);
                    $row = mysqli_fetch_assoc($result);	
					
                    print(mysqli_error($link));
                    ?>
                    <div class="aantalzoek">
                        aantal producten per pagina: <select name="perpage" onchange="this.form.submit()" form="select">
                            <option value="10"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 10) echo "selected"; ?>>10</option>
                            <option value="20"<?php
                            if (!empty($_GET["perpage"]) && $_GET["perpage"] == 20) {
                                echo "selected";
                            } elseif (empty($_GET["perpage"])) {
                                echo 'selected';
                            }
                            ?>>20</option>
                            <option value="25"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 25) echo "selected"; ?>>25</option>
                            <option value="50"<?php if (!empty($_GET["perpage"]) && $_GET["perpage"] == 50) echo "selected"; ?>>50</option>
                        </select>




                <?php
                if (!mysqli_num_rows($resultcount) == 0) {
                    print(" Aantal resultaten: " . mysqli_num_rows($resultcount));
                }
                ?></div><?php		
                    // zorgt ervoor dat de date functie de juiste timezone gebruikt
                    if (function_exists('date_default_timezone_set')) {
                        date_default_timezone_set('Europe/Amsterdam');
                    }

                    // Weergeeft alle bestellingen
                    print('<table class="table_administratie"><tr><th>Bestelnr</th><th>Klantnr</th><th>Status</th><th>Besteldatum</th><th>Bezorgdatum</th><th>Transactie Referentie</th><th>Aantal Artikelen</th><th>Verwijderen</th><th>Aanpassen</th><th>Bekijken</th></tr>');
                    while ($row) {
                        $result2 = mysqli_query($link, 'SELECT SUM(aantal) hoeveelheid FROM bestelregel WHERE bestelnr = "' . $row["bestelnr"] . '" GROUP BY bestelnr;');
                        $row2 = mysqli_fetch_assoc($result2);
                        print('<tr><td>' . $row["bestelnr"] . '</td>'
                                . '<td>' . $row["klantnr"] . '</td>'
                                . '<td>' . $row["status"] . '</td>'
                                . '<td>' . date("d-m-Y", strtotime($row["besteldatum"])) . '</td>'
                                . '<td>' . date("d-m-Y", strtotime($row["bezorgdatum"])) . '</td>'
                                . '<td>' . $row["transactieref"] . '</td>'
                                . '<td>' . $row2["hoeveelheid"] . '</td>'
                                . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="bestelnr" value="' . $row["bestelnr"] . '"><input type="submit" name="actie" value="Verwijderen" onClick="return checkDelete();"></form></td>'
                                . '<td><form action="" method="POST" class="table_administratie_button" ><input type="hidden" name="bestelnr" value="' . $row["bestelnr"] . '"><input type="submit" name="actie" value="Aanpassen">	</form></td>'
                                . '<td><form action="bestelling.php?bestelnr=' . $row["bestelnr"] . '" method="POST" class="table_administratie_button" ><input type="hidden" name="bestelnr" value="' . $row["bestelnr"] . '"><input type="submit" name="actie" value="Bekijken"></form></td></tr>');
                        $row = mysqli_fetch_assoc($result);
                    }
                    print("</table>");
                    ?>
					<?php if(($pages / $perpage +1) !=1){ 
			print('<input type="submit" name="action" value="<" form="select">');
		} ?>	
            <select name="pages" onchange="this.form.submit()" form="select">
                <?php
                for ($i = 0; $i < $amount; $i++) {
                    $x = $i + 1;
                    if ($pages == ($i * $perpage)) {
                        print('<option value="' . $i * $perpage . '" selected >' . $x . '</option>');
                    } else {
                        print('<option value="' . $i * $perpage . '" >' . $x . '</option>');
                    }
                }
                ?>
            </select>
            <?php
            if (!empty($_GET["pages"])) {
                print "<input type='hidden' name='ref' value='" . $_GET["pages"] . "' form='select' >";
            } else {
                print "<input type='hidden' name='ref' value='0' form='select' >";
            }
            ?>
			<?php if(($pages / $perpage +1) != round($amount)){ 	
				print('<input type="submit" name="action" value=">" form="select">');
			 } ?>

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
