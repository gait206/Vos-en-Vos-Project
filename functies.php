<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//
//    Cookie functies
//
// Notes:
// Cookies kunnen alleen veranderd of verwijderd worden door ze opnieuw te setten
// maakt een nieuwe cookie aan
function addCookie($name, $array) {
    // kijkt of de ingevoerde waarde $array een array is
    if (is_array($array)) {
        // zet array om naar string
        $value = json_encode($array);
        if ($value) {
            // de cookie blijft 24 uur bestaan
            $domain = "/";
            return setcookie($name, $value, time() + (24 * 60 * 60), $domain, false);
        } else {
            return "De array kon niet omgezet worden naar een string";
        }
        // is true als het maken van de cookie is gelukt
        return true;
    } else {
        return "De ingevoerde waarde is geen array";
    }
}

// stdObject naar array
function objectToArray($object) {
    if (!is_object($object) && !is_array($object)) {
        return $object;
    }
    if (is_object($object)) {
        $object = get_object_vars($object);
    }
    return array_map('objectToArray', $object);
}

// kijkt of de cookie bestaat
function existCookie($name) {

    if (isset($GLOBALS["_COOKIE"][$name])) {
        return true;
    } else {
        return false;
    }
}

// past een regel in de array aan
function modifyCookieLine($name, $key, $value) {
    $cookie = getCookie($name);
    $cookie = objectToArray($cookie);
    if (is_array($cookie)) {
        if (array_key_exists($key, $cookie)) {
            $cookie[$key] = $value;
            saveCookie($name, $cookie);
            return true;
        } else {
            return "Deze key bestaat niet";
        }
    } else {
        return "Deze cookie is geen array";
    }
}

// voegt een regel aan de array toe
function addCookieLine($name, $key, $value) {
    if (existCookie($name)) {
        $cookie = getCookie($name);
        $cookie[$key] = $value;
        saveCookie($name, $cookie);
        return true;
    } else {
        return "Deze cookie bestaat niet";
    }
}

// verwijderd een regel van de cookie
function removeCookieLine($name, $key) {
    if (existCookie($name)) {
        $cookie = getCookie($name);
        if (array_key_exists($key, $cookie)) {
            unset($cookie[$key]);
            saveCookie($name, $cookie);
        } else {
            return "Deze key bestaat niet";
        }
    } else {
        return "Deze cookie bestaat niet";
    }
}

// verwijderd de cookie
function deleteCookie($name) {
    if (existCookie($name)) {
        unset($_COOKIE[$name]);
        setcookie($name, null, time() + 1, '/', false);
        return true;
    } else {
        return "Deze cookie bestaat niet";
    }
}

// returnt de cookie
function getCookie($name) {
    if (existCookie($name)) {
        $cookie = $_COOKIE[$name];
        // verwijderd de slashes die automatisch gemaakt worden bij setcookie()
        $cookie = stripslashes($cookie);
        // hij mag niet 2 keer decoden
        $cookie = json_decode($cookie);
        $cookie = objectToArray($cookie);
        return $cookie;
    } else {
        return "Deze cookie bestaat niet";
    }
}

// cookie opslaan
function saveCookie($name, $array) {
    if (existCookie($name)) {
        addCookie($name, $array);
        return true;
    } else {
        return "Deze cookie bestaat niet";
    }
}

function base_query_generate($switch) {
    $query = "SELECT * FROM product ";

    switch ($switch) {
        case 0 : $query .= 'WHERE categorie = "papier" ';
            break;
        case 1 : $query .= 'WHERE categorie = "dispensers" ';
            break;
        case 2 : $query .= 'WHERE categorie = "reinigingsmiddelen" ';
            break;
        case 3 : $query .= 'WHERE categorie = "schoonmaakmateriaal" ';
            break;
        case 4 : $query .= 'WHERE categorie IN ("papier","dispensers","reinigingsmiddelen","schoonmaakmateriaal") ';
    }
    return $query;
}

function filter_query_generate($query, $switch, array $checkbox) {
    $count = count($checkbox);

    switch ($switch) {
        case 0 : $query .= '';
            break;
        case 1 : $query .= 'AND prijs < 10.00 ';
            break;
        case 2 : $query .= 'AND prijs BETWEEN 10.00 AND 20.00 ';
            break;
        case 3 : $query .= 'AND prijs BETWEEN 20.00 AND 30.00 ';
            break;
        case 4 : $query .= 'AND prijs BETWEEN 30.00 AND 50.00 ';
            break;
        case 5 : $query .= 'AND prijs BETWEEN 50.00 AND 75.00 ';
            break;
        case 6 : $query .= 'AND prijs BETWEEN 75.00 AND 100.00 ';
            break;
        case 7 : $query .= 'AND prijs BETWEEN 100.00 AND 200.00 ';
            break;
        case 8 : $query .= 'AND prijs > 200.00 ';
            break;
    }
    if ($count == 1) {
        $query .= "AND subcategorie = '" . $checkbox["0"] . "' ";
    } else {
        $countarray = 1;
        foreach ($checkbox as $key => $value) {
            if ($countarray == 1) {
                $query .= "AND subcategorie IN('" . $checkbox[$key] . "',";
            } else if ($countarray < $count) {
                $query .= '"' . $checkbox[$key] . '",';
            } else {
                $query .= '"' . $checkbox[$key] . '") ';
            }
            $countarray = $countarray + 1;
        }
    }

    return($query);
}

function search_query_generate($search_term, $query) {
    $query .= 'AND (productnaam LIKE "%' . $search_term . '%" OR
					inhoud LIKE "%' . $search_term . '%" OR
                    productnr = "' . $search_term . '" OR
                    omschrijving LIKE "%' . $search_term . '%")';

    return($query);
}

function sort_query_generate($query, $switch) {
    if ($switch > 0) {
        $query .= ' ORDER BY ';
    }
    switch ($switch) {
        case 0 :;
            break;
        case 1 : $query .= 'merk ';
            break;
        case 2 : $query .= 'merk DESC ';
            break;
        case 3 : $query .= 'prijs ';
            break;
        case 4 : $query .= 'prijs DESC ';
            break;
        case 5 : $query .= 'categorie ';
            break;
        case 6 : $query .= 'categorie DESC ';
            break;
    }
    return($query);
}

function amount_per_page($result, $perpage) {
    $count = mysqli_num_rows($result);
    $amount = $count / $perpage;

    if ($amount <= 1) {
        $amount = 1;
    } else {
        ceil($amount);
    }
    return $amount;
}

function limit_query_generate($page, $query, $perpage) {
    $query .= "LIMIT " . $page . "," . $perpage;
    return $query;
}

function selected($switch, $number) {
    if (isset($_POST[$switch])) {
        $switch = $_POST["switch"];
        if ($number == $switch) {
            print(" selected");
        }
    }
    return;
}

function isin(array $x, $y) {
    foreach ($x as $value) {
        if ($y == $value) {
            print("checked");
        }
    }
    return;
}

function validToken($link) {
    deleteDatabaseToken($link);
    // kijkt of gebruiker ingelogd is
    $ip = $_SERVER["REMOTE_ADDR"];
    $userAgent = $_SERVER["HTTP_USER_AGENT"];
    $result = mysqli_query($link, 'SELECT * FROM token WHERE ip = "' . $ip . '";');
    if (mysqli_error($link)) {
        return "Error: " . mysqli_error($link);
    } else {
        // vergelijkt waarden beide tokens (in database en in sessie)
        $row = mysqli_fetch_assoc($result);
        // hierna gaat het fout de isset is false
        if (isset($_SESSION["token"])) {
            deleteToken(false, $link);
            if (isset($_SESSION["token"])) {
                $random = $_SERVER['HTTP_USER_AGENT'];
                if ($_SESSION["token"] == $row["token"] && $row["token"] == crypt($random, $row["token"])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }
}

function createToken($klantnr, $link) {
    $ip = $_SERVER["REMOTE_ADDR"];
    $size = 60;
    $random = $_SERVER['HTTP_USER_AGENT'];
    $salt = '$6$rounds=5000$';
    $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
    $token = crypt($random, $salt);

    $_SESSION["token"] = $token;
    $_SESSION["created"] = time();
    if ($link == true) {

        mysqli_query($link, 'INSERT INTO token VALUES("' . $klantnr . '", "' . $token . '", "' . $ip . '");');
        if (mysqli_error($link)) {
            return "Error: " . mysqli_error($link);
        }
        return true;
    } else {
        return false;
    }
}

function updateToken($link) {
    if (validToken($link) == true) {
        $_SESSION["created"] = time();
        return true;
    } else {
        return false;
    }
}

function getKlantnr($link) {
    if (isset($_SESSION["token"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $token = $_SESSION["token"];
        $stmt = mysqli_prepare($link, 'SELECT klantnr FROM token WHERE ip = ? AND token = ?;');
        mysqli_stmt_bind_param($stmt, 'ss', $ip, $token);
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $klantnr);
        if (mysqli_stmt_error($stmt)) {
            return "Error: " . mysqli_stmt_error($stmt);
        } else {
            mysqli_stmt_fetch($stmt);
            return $klantnr;
        }
    } else {
        return false;
    }
}

function userLevel($klantnr, $link) {
    $stmt = mysqli_prepare($link, 'SELECT level FROM gebruiker WHERE klantnr = ?;');
    mysqli_stmt_bind_param($stmt, 'i', $klantnr);
    mysqli_execute($stmt);
    mysqli_stmt_bind_result($stmt, $level);
    if (mysqli_stmt_error($stmt)) {
        return "Error: " . mysqli_stmt_error($stmt);
    } else {
        mysqli_stmt_fetch($stmt);
        return $level;
    }
}

function encryptPassword($password) {
    $size = 60;
    $salt = '$6$rounds=5000$';
    // strtr() convert alle + tekens naar . tekens
    $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
    $hashed = crypt($password, $salt);
    return $hashed;
}

function verifyPassword($email, $password, $link) {
    if ($link == true) {
        $stmt = mysqli_prepare($link, 'SELECT wachtwoord FROM gebruiker WHERE email = ?;');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $wachtwoord);
        if (mysqli_stmt_error($stmt)) {
            return "Error: " . mysqli_stmt_error($stmt);
        } else {
            mysqli_stmt_fetch($stmt);
            $password2 = crypt($password, $wachtwoord);
            //print(encryptPassword("$password"));
            if ($wachtwoord == $password2) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function connectDB() {
    $host = "localhost";
    $user = "root";
    $password = "usbw";
    $database = "vvtissue";
    $port = 3307;

    $link = mysqli_connect($host, $user, $password, $database, $port);
    if (mysqli_connect_error()) {
        return "Error: " . mysqli_connect_error();
    } else {
        return $link;
    }
}

// geeft enorm veel errors als hij gebruik maakt van (time() - $_SESSION["created"] > 1800)
// als je true gebruikt werkt hij wel dus het is een verloop error
function deleteToken($verwijderen, $link) {
    if (isset($_SESSION["token"])) {
        if ($verwijderen || (time() - $_SESSION["created"] > 1800)) {
            // getEmail is het probleem
            $klantnr = getKlantnr($link);
            $stmt = mysqli_prepare($link, 'DELETE FROM token WHERE klantnr = ?;');
            mysqli_stmt_bind_param($stmt, 'i', $klantnr);
            mysqli_execute($stmt);
            unset($_SESSION);
            session_destroy();
        }
    }
}

function restrictedPage($level, $link) {
    if (validToken($link) == true) {
        if (userLevel(getKlantnr($link), $link) == $level) {
            if (mysqli_connect_error($link)) {
                return "Error: " . mysqli_connect_error($link);
            } else {
                updateToken($link);
                return true;
            }
        } else {
            header('Location: ../index.php');
            die();
            return false;
        }
    } else {
        header('Location: ../index.php');
        die();
        return false;
    }
}

function deleteDatabaseToken($link) {
    if (!isset($_SESSION["token"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $result = mysqli_query($link, 'SELECT * FROM token WHERE ip = "' . $ip . '";');
        $row = mysqli_fetch_assoc($result);
        if (mysqli_error($link) || $row["ip"]) {
            if (mysqli_error($link)) {
                return "Error: " . mysqli_error($link);
            } else {
                mysqli_query($link, 'DELETE FROM token WHERE ip = "' . $ip . '";');
                return true;
            }
        }
    }
}

function verifyPasswordForgot($email, $token2, $link) {
    $stmt = mysqli_prepare($link, 'SELECT token, datum FROM recovery WHERE email = ?;');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $token, $datum);
    mysqli_stmt_fetch($stmt);

    // als de url 24 uur oud is word hij verwijderd
    if (($datum - time()) > (60 * 60 * 24)) {
        deleteDatabaseToken($link);
    } else {
        if ($token == $token2) {
            return true;
        } else {
            return false;
        }
    }
}

function prijsformat($prijs) {
    return number_format($prijs, 2, ",", ".");
}

function prijsber($prijs) {
    return number_format($prijs * 1.21, 2, ",", ".");
}

// Validatiecontrole emailadres
//function validate_email($email, $strict = true) {
//    $dot_string = $strict ?
//            '(?:[A-Za-z0-9!#$%&*+=?^_`{|}~\'\\/-]|(?<!\\.|\\A)\\.(?!\\.|@))' :
//            '(?:[A-Za-z0-9!#$%&*+=?^_`{|}~\'\\/.-])'
//    ;
//    $quoted_string = '(?:\\\\\\\\|\\\\"|\\\\?[A-Za-z0-9!#$%&*+=?^_`{|}~()<>[\\]:;@,. \'\\/-])';
//    $ipv4_part = '(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])';
//    $ipv6_part = '(?:[A-fa-f0-9]{1,4})';
//    $fqdn_part = '(?:[A-Za-z](?:[A-Za-z0-9-]{0,61}?[A-Za-z0-9])?)';
//    $ipv4 = "(?:(?:{$ipv4_part}\\.){3}{$ipv4_part})";
//    $ipv6 = '(?:' .
//            "(?:(?:{$ipv6_part}:){7}(?:{$ipv6_part}|:))" . '|' .
//            "(?:(?:{$ipv6_part}:){6}(?::{$ipv6_part}|:{$ipv4}|:))" . '|' .
//            "(?:(?:{$ipv6_part}:){5}(?:(?::{$ipv6_part}){1,2}|:{$ipv4}|:))" . '|' .
//            "(?:(?:{$ipv6_part}:){4}(?:(?::{$ipv6_part}){1,3}|(?::{$ipv6_part})?:{$ipv4}|:))" . '|' .
//            "(?:(?:{$ipv6_part}:){3}(?:(?::{$ipv6_part}){1,4}|(?::{$ipv6_part}){0,2}:{$ipv4}|:))" . '|' .
//            "(?:(?:{$ipv6_part}:){2}(?:(?::{$ipv6_part}){1,5}|(?::{$ipv6_part}){0,3}:{$ipv4}|:))" . '|' .
//            "(?:(?:{$ipv6_part}:){1}(?:(?::{$ipv6_part}){1,6}|(?::{$ipv6_part}){0,4}:{$ipv4}|:))" . '|' .
//            "(?::(?:(?::{$ipv6_part}){1,7}|(?::{$ipv6_part}){0,5}:{$ipv4}|:))" .
//            ')';
//    $fqdn = "(?:(?:{$fqdn_part}\\.)+?{$fqdn_part})";
//    $local = "({$dot_string}++|(\"){$quoted_string}++\")";
//    $domain = "({$fqdn}|\\[{$ipv4}]|\\[{$ipv6}]|\\[{$fqdn}])";
//    $pattern = "/\\A{$local}@{$domain}\\z/";
//    return preg_match($pattern, $email, $matches) &&
//            (
//            !empty($matches[2]) && !isset($matches[1][66]) && !isset($matches[0][256]) ||
//            !isset($matches[1][64]) && !isset($matches[0][254])
//            )
//    ;
//}
// Controleren voor geldig BTW nummer
// een geldig btw nummer is: NL 1535.50.909.B02

function checkBTW($btwnummer) {
    $remove = str_replace(" ", "", $btwnummer);
    $upper = strtoupper($remove);

    if (preg_match("/^NL[0-9]{9}B[0-9]{2}$/", $upper)) {
        return $upper;
    } else {
        return false;
    }
}

// Als de cookie niet bestaat werkt het niet
function countItems($array) {
    if (isset($_COOKIE["winkelmandje"])) {
        $aantal = 0;
        foreach ($array as $key => $value) {
            $aantal = $aantal + $value;
        }
        return $aantal;
    } else {
        return 0;
    }
}

function PostcodeCheck($postcode) {
    $remove = str_replace(" ", "", $postcode);
    $upper = strtoupper($remove);

    if (preg_match("/^\W*[1-9]{1}[0-9]{3}\W*[a-zA-Z]{2}\W*$/", $upper)) {
        return $upper;
    } else {
        return false;
    }
}

function CheckEmailExists($emailexists, $link) {

    $query = mysqli_query($link, "SELECT email FROM gebruiker WHERE email = '" . $emailexists . "'");
    $rows = mysqli_num_rows($query);

    if ($rows == 0) {
        return true;
    } else {
        return false;
    }
}

function accountBlocked($email, $link) {
    $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "' . $email . '";');
    if (mysqli_error($link)) {
        return mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($result);
        $klantnr = $row["klantnr"];
        $result2 = mysqli_query($link, 'SELECT poging FROM geblokkeerd WHERE klantnr = "' . $klantnr . '";');
        $row2 = mysqli_fetch_assoc($result2);

        if ($row2["poging"] >= 5) {
            return true;
        } else {
            return false;
        }
    }
}

// telt het aantal foute loginpogingen op en blokkeert het account
function accountBlockedCount($email, $link) {
    $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "'.$email.'";');

    if (mysqli_error($link)) {
        return mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($result);
        $klantnr = $row["klantnr"];
        
        $result2 = mysqli_query($link, 'SELECT poging FROM geblokkeerd WHERE klantnr = "' . $klantnr . '";');
        $row2 = mysqli_fetch_assoc($result2);
        $rows = mysqli_num_rows($result2);
        print(mysqli_error($link));

        if ($rows == 0) {
            mysqli_query($link, 'INSERT INTO geblokkeerd(klantnr,poging) VALUES("' . $klantnr . '",1)');
        } else {
            mysqli_query($link, 'UPDATE geblokkeerd SET poging=poging+1 WHERE klantnr = "' . $klantnr . '";');



            if ($row2["poging"] == 4) {
                $size = 60;
                $random = strtr(base64_encode(mcrypt_create_iv($size)), '+', '.');
                $salt = '$6$rounds=5000$';
                $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
                $token = crypt($random, $salt);

                mysqli_query($link, 'UPDATE geblokkeerd SET token = "' . $token . '" WHERE klantnr = "' . $klantnr . '";');
                
                $url = 'http://localhost:8080/login/onblokkeer.php?klantnr='.$klantnr.'&token='.$token;
                $message = '<html><head></head><body>Iemand heeft vijf keer met een verkeerd wachtwoord ingelogd op uw account <a href="'.$url.'">Klik op deze link</a> om uw account te onblokkeren.</body></html>';
                
                date_default_timezone_set("UTC");
                mail($email, 'Account geblokkeerd', $message, 'From:admin@gmail.com');
            }
        }
    }
}
// encrypt data
function encryptData($data){
    // global encryption key
    $key = '5mEhXwWt/LqJ8pw5QfduqTz0h7E=';
    
    $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB));
    
    return $encrypted;
}
// decrypt data
function decryptData($data){
        // global encryption key
        $key = '5mEhXwWt/LqJ8pw5QfduqTz0h7E=';
        
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB);
        
        return $decrypted;
}

// maakt een factuur aan van een bestelling
function createFactuur($name, $bestelnr) {
require('fpdf/fpdf.php');

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Helvetica', 'B', 16);

$link = connectDB();

$result = mysqli_query($link, 'SELECT * FROM bestelling WHERE bestelnr = "' . $bestelnr . '";');
$row = mysqli_fetch_assoc($result);

$pdf->Image('../plaatjes/logo.png', 12, 6, 40);
$pdf->Ln(4);
$pdf->Cell(8, 30, 'Bestelnr: ' . $bestelnr);


$result2 = mysqli_query($link, 'SELECT * FROM anderadres WHERE bestelnr = "' . $bestelnr . '";');
$result3 = mysqli_query($link, 'SELECT voornaam, achternaam, bedrijfsnaam, adres, plaats, postcode FROM klant AS k JOIN bestelling AS b ON k.klantnr = b.klantnr WHERE bestelnr = "' . $bestelnr . '";');
$row3 = mysqli_fetch_assoc($result3);

// print de bedrijfsgegevens
$pdf->Ln(1);
$pdf->Cell(42, 40, 'Bedrijf: ');
$pdf->Cell(8, 40, $row3["bedrijfsnaam"]);
$pdf->Ln(6);
$pdf->Cell(42, 40, 'Ter Name Van: ');
$pdf->Cell(8, 40, $row3["voornaam"]." ".$row3["achternaam"]);
$pdf->Ln(10);
$pdf->Cell(25, 40, 'Afleveradres: ');


$pdf->SetFont('Helvetica', 'B', 12);

// print het afleveradres
if (mysqli_num_rows($result2) == 1) {

    $row2 = mysqli_fetch_assoc($result2);

    $pdf->Ln(10);
    $pdf->Cell(25, 40, 'Plaats: ');
    $pdf->Cell(2, 40, $row2["plaats"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Adres: ');
    $pdf->Cell(2, 40, $row2["adres"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Postcode: ');
    $pdf->Cell(2, 40, $row2["postcode"]);
} else {
    $pdf->Ln(10);
    $pdf->Cell(25, 40, 'Plaats: ');
    $pdf->Cell(2, 40, $row3["plaats"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Adres: ');
    $pdf->Cell(2, 40, $row3["adres"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Postcode: ');
    $pdf->Cell(2, 40, $row3["postcode"]);
}

$result4 = mysqli_query($link, 'SELECT * FROM bestelregel AS b JOIN product AS p ON b.productnr = p.productnr WHERE bestelnr = "'.$bestelnr.'";');
$row4 = mysqli_fetch_assoc($result4);

$pdf->SetFont('Helvetica', 'B', 10);

// print de kop
    $pdf->Ln(30);
    $pdf->Cell(20, 10, 'Productnr', 1);
    $pdf->Cell(110, 10, 'Productnaam', 1);
    $pdf->Cell(15, 10, 'Prijs', 1);
    $pdf->Cell(15, 10, 'Aantal', 1);
    $pdf->Cell(25, 10, 'Totale Prijs', 1);

    $totaalBedrag = 0;
    $totaalBTW = 0;
    $totaalBedragBTW = 0;
    
    // laat alle producten met hun prijzen zien
while($row4){
    $pdf->Ln(10);
    $pdf->Cell(20, 10, $row4["productnr"], 1);
    $pdf->Cell(110, 10, $row4["productnaam"], 1);
    $pdf->Cell(15, 10, $row4["prijs"], 1);
    $pdf->Cell(15, 10, $row4["aantal"], 1);
    $pdf->Cell(25, 10, number_format($row4["prijs"] * $row4["aantal"], 2), 1);
    
    $totaalBedrag = $totaalBedrag + ($row4["prijs"] * $row4["aantal"]);
    $row4 = mysqli_fetch_assoc($result4);
}

$totaalBTW = $totaalBedrag * 0.21;
$totaalBedragBTW = $totaalBTW + $totaalBedrag;

// zorgt dat de totalen worden weergegeven
$pdf->Ln(10);
$pdf->SetLeftMargin(140);
$pdf->Cell(35, 10, 'Totaal:');
$pdf->Cell(35, 10, number_format($totaalBedrag,2));
$pdf->Ln(10);
$pdf->Cell(35, 10, 'Totaal BTW:');
$pdf->Cell(35, 10, number_format($totaalBTW,2));
$pdf->Ln(10);
$pdf->Cell(35, 10, 'Totaal Bedrag:');
$pdf->Cell(35, 10, number_format($totaalBedragBTW,2));

$pdf->Output('facturen/'.$name ,'F');

}