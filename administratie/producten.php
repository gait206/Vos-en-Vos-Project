<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/producten.css">
        <title></title>

    </head>
    <body>
        <?php
        if (isset($_GET["merk"])) {
            $merk = $_GET["merk"];
        } else {
            $merk = array();
        }
        ?>
        <div class="navigator">


            <div class="navigatie">
                <form action="index.php" method="get" id="select">
                    <input class="zoekinput" type="text" placeholder="Zoek" name="zoekbalk" <?php if(isset($_GET['zoekbalk'])){ print('value="'.$_GET['zoekbalk'].'"'); } ?>>
                    <input class="zoeksubmit" type="submit" value="Zoek" name="zoekknop"><br>
                    <h4>Selecteer merk(en):</h4>
                    <input type="checkbox"  name="merk[]"  value="Katrin" <?php if (in_array("Katrin", $merk)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Katrin<br>
                    <input type="checkbox"  name="merk[]"  value="Blanco" <?php if (in_array("Blanco", $merk)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Blanco<br>
                    <input type="checkbox"  name="merk[]"  value="Tana" <?php if (in_array("Tana", $merk)) echo "checked ='checked'"; ?>onclick="this.form.submit()";>Tana<br>
                    <input type="checkbox"  name="merk[]"  value="Eurotissue" <?php if (in_array("Eurotissue", $merk)) echo "checked ='checked'"; ?> onclick="this.form.submit()";>Eurotissue<br>
                    <h4>Selecteer prijscategorie:</h4>
                    <select name="prijs" form="select" onchange="this.form.submit()">
                        <option value=0 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 0) echo "selected"; ?>>Selecteer prijs </option>
                        <option value=1 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 1) echo "selected"; ?>>< 10.00</option>
                        <option value=2 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 2) echo "selected"; ?>>10.00 ... 20.00</option>
                        <option value=3 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 3) echo "selected"; ?>>20.00 ... 30.00</option>
                        <option value=4 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 4) echo "selected"; ?>>30.00 ... 50.00</option>
                        <option value=5 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 5) echo "selected"; ?>>50.00 ... 75.00</option>
                        <option value=6 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 6) echo "selected"; ?>>75.00 ... 100.00</option>
                        <option value=7 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 7) echo "selected"; ?>>100.00 ... 200.00</option>
                        <option value=8 <?php if (isset($_GET['prijs']) && $_GET["prijs"] == 8) echo "selected"; ?>>> 200.00</option>
                    </select><br>
                    <h4>Sorteer op:</h4>
                    <select name="sort" form="select" onchange="this.form.submit()">
                        <option value=0 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 0) echo "selected"; ?>>Niet gesorteerd</option>
                        <option value=1 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 1) echo "selected"; ?>>merk (oplopend)</option>
                        <option value=2 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 2) echo "selected"; ?>>merk (aflopend)</option>
                        <option value=3 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 3) echo "selected"; ?>>prijs (oplopend)</option>
                        <option value=4 <?php if (isset($_GET['prijs']) && $_GET["sort"] == 4) echo "selected"; ?>>prijs (aflopend)</option>
                    </select>
                </form>

                <?php
                // code to get the right variables into the right place in the functions and such
                $query = "";
                if (isset($_GET['merk']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop'])) {
	
                    if (isset($_GET['merk'])) {
                        $checkbox = $_GET['merk'];
                    } else {
                        $checkbox = array();
                    }
                    $switch = $_GET['prijs'];
                    $sort = $_GET['sort'];
                    $query = filter_query_generate($switch, $checkbox);
                    if (isset($_GET['zoekknop']) || isset($_GET['zoekbalk'])) {
					if (!$_GET['zoekbalk'] == ""){
                        $query = search_query_generate($_GET['zoekbalk'], $query);
                    }}
                    $query = sort_query_generate($query, $sort);
                }
                ?>

            </div>
        </div>

        <div class="body" id="main_content">
            <?php
// Create connection
            $conn = connectDB();
            if (!(isset($_GET['merk']) || isset($_GET['prijs']) || isset($_GET['sort']) || isset($_GET['zoekknop']))) {
                if ($query == "") {
                    
					$query = "SELECT afbeelding, productnaam, omschrijving, merk, prijs FROM product";

                }
            }
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                print("<table><tr>
			  <td><strong>Afbeelding</strong></td>
              <td><strong>Titel</strong></td>
			  <td><strong>Omschrijving</strong></td>
              <td><strong>Merk</strong></td>         
              <td><strong>Prijs</strong></td>
              </tr>");


                while ($row) {
                    print("<tr>
			  <td>" . $row['afbeelding'] . "</td>
              <td>" . $row['productnaam'] . "</td>
              <td>" . $row['omschrijving'] . "</td>
			  <td>" . $row['merk'] . "</td>
              <td>" . $row['prijs'] . "</td>
              </tr>");
                    $row = mysqli_fetch_assoc($result);
                }
				?>

                <aantalzoek style = "text-align:right; display:block; color: #344d8e; font-size:12px;">
                
                <?php
				print("Aantal resultaten: " .mysqli_num_rows($result));
            ?>
				</aantalzoek> 

        </table>
    </div>


</body>
</html>
