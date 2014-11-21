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
        <div class="navigator">


            <div class="navigatie">
                <form action="index.php" method="get" id="select">
                    <input class="zoekinput" type="text" placeholder="Zoek" name="zoekbalk"><br>
                    <h4>Merken</h4>
                    <input type="checkbox"  name="merk[]"  value="Katrin"       >Katrin<br>
                    <input type="checkbox"  name="merk[]"  value="Blanco"       >Blanco<br>
                    <input type="checkbox"  name="merk[]"  value="Tana"         >Tana<br>
                    <input type="checkbox"  name="merk[]"  value="Eurotissue"   >Eurotissue<br>

                    <select name="prijs" form="select">
                        <option value=0>prijs select</option>
                        <option value=1>10.00 ... 20.00</option>
                        <option value=2>20.00 ... 30.00</option>
                        <option value=3>30.00 ... 50.00</option>
                        <option value=4>50.00 ... 75.00</option>
                        <option value=5>75.00 ... 100.00</option>
                        <option value=6>100.00 ... 200.00</option>
                        <option value=7>< 200.00</option>
                    </select><br>
                    <select name="sort" form="select">
                        <option value=0>unorderd</option>
                        <option value=1>merk</option>
                        <option value=2>merk (desc)</option>
                        <option value=3>prijs</option>
                        <option value=4>prijs (desc)</option>
                    </select><br><br>
                    <input class="zoeksubmit" type="submit" value="Zoek" name="zoekknop">
                </form>

                <?php
                // code to get the right variables into the right place in the functions and such
                include 'functies.php';
                $query = "";
                if (isset($_GET['zoekknop'])) {
                    if (isset($_GET['merk'])) {
                        $checkbox = $_GET['merk'];
                    } else {
                        $checkbox = array();
                    }
                    $switch = $_GET['prijs'];
                    $sort = $_GET['sort'];
                    $query = filter_query_generate($switch, $checkbox);
                    if (isset($_GET['zoekbalk'])) {
                        if (!$_GET['zoekbalk'] == '') {
                            $query = search_query_generate($_GET['zoekbalk'], $query);
                        }
                    }
                    $query = sort_query_generate($query, $sort);
                }
                
                //print ($query);
                ?>

            </div>
        </div>

        <div class="body" id="main_content">
<?php
$host = "localhost";
$user = "root";
$password = "usbw";
$database = "vvtissue";
$port = 3307;
$zoekwoord;
// Create connection
$conn = mysqli_connect($host, $user, $password, $database, $port);
if ($query == "") {
                    $query = "SELECT * FROM product";
                }

if (isset($_GET['zoekknop'])) {
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    print("<table><tr>
              <td><strong>Productnummer</strong></td>
              <td><strong>Naam</strong></td>
              <td><strong>Merk</strong></td>
              <td><strong>Categorie</strong></td>
              <td><strong>Omschrijving</strong></td>
              <td><strong>Afbeelding</strong></td>
              <td><strong>Prijs</strong></td>
              </tr>");

    while ($row) {
        print("<tr>
              <td>" . $row['productnr'] . "</td>
              <td>" . $row['productnaam'] . "</td>
              <td>" . $row['merk'] . "</td>
              <td>" . $row['categorie'] . "</td>
              <td>" . $row['omschrijving'] . "</td>
              <td>" . $row['afbeelding'] . "</td>
              <td>" . $row['prijs'] . "</td>
              </tr>");
        $row = mysqli_fetch_assoc($result);
    }
}
?>



        </table>
    </div>


</body>
</html>
