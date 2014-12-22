<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//
//    Cookie functies
//

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

// veranderd een object in een array
function objectToArray($object) {
    // kijkt of het ingevoerde variabel geen array of een object is
    if (!is_object($object) && !is_array($object)) {
        return 'Dit is geen array of object';
    }
    // kijkt of het ingevoerde variabel een object is
    if (is_object($object)) {
        // zet een object om in een array
        $object = get_object_vars($object);
    }
    // haalt de array door de functie totdat het geen object meer is
    return array_map('objectToArray', $object);
}

// kijkt of de cookie bestaat
function existCookie($name) {
// kijkt of er een cookie met een naam bestaat in de globale variabelen
    if (isset($GLOBALS["_COOKIE"][$name])) {
        return true;
    } else {
        return false;
    }
}

// past een regel in de array aan
function modifyCookieLine($name, $key, $value) {
    $cookie = getCookie($name);
    // kijkt of de key in de array bestaat
        if (array_key_exists($key, $cookie)) {
            $cookie[$key] = $value;
            saveCookie($name, $cookie);
            return true;
        } else {
            return "Deze key bestaat niet";
        }
}

// voegt een regel aan de array toe
function addCookieLine($name, $key, $value) {
    // kijkt of de cookie bestaat
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
    // kijkt of de cookie bestaat
    if (existCookie($name)) {
        $cookie = getCookie($name);
        // kijkt of de key in de array bestaat
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
    // kijkt of de cookie bestaat
    if (existCookie($name)) {
        unset($_COOKIE[$name]);
        setcookie($name, null, time() + 1);
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
        $cookie = json_decode($cookie);
        $cookie = objectToArray($cookie);
        return $cookie;
    } else {
        return "Deze cookie bestaat niet";
    }
}

// cookie opslaan
function saveCookie($name, $array) {
    // kijkt of de cookie bestaat
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

// word deze functie wel gebruikt?????
function selected($switch, $number) {
    if (isset($_POST[$switch])) {
        $switch = $_POST["switch"];
        if ($number == $switch) {
            print(" selected");
        }
    }
    return;
}

// word deze functie wel gebruikt?????
function isin(array $x, $y) {
    foreach ($x as $value) {
        if ($y == $value) {
            print("checked");
        }
    }
    return;
}

// kijkt of een token geldig is
function validToken($link) {
    // verwijderd eventueel overgebleven gegevens van een vorige sessie
    deleteDatabaseToken($link);
    $ip = $_SERVER["REMOTE_ADDR"];
    $userAgent = $_SERVER["HTTP_USER_AGENT"];
    $result = mysqli_query($link, 'SELECT * FROM token WHERE ip = "' . $ip . '";');
    if (mysqli_error($link)) {
        return "Error: " . mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($result);
        // kijkt of het lokale token bestaat
        if (isset($_SESSION["token"])) {
            // verwijderd het token als deze is verlopen
            deleteToken(false, $link);
            // kijkt of het lokale token dan nog bestaat
            if (isset($_SESSION["token"])) {
                $random = $_SERVER['HTTP_USER_AGENT'];
                // kijkt of de tokens overeen komen en of de browser nog overeen komt
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

// maakt een nieuw token aan
function createToken($klantnr, $link) {
    $ip = $_SERVER["REMOTE_ADDR"];
    $size = 60;
    $random = $_SERVER['HTTP_USER_AGENT'];
    $salt = '$6$rounds=5000$';
    $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
    $token = crypt($random, $salt);

    $_SESSION["token"] = $token;
    $_SESSION["created"] = time();
    // kijkt of er een verbinding is met de database
    if ($link == true) {

        mysqli_prepare($link, 'INSERT INTO token VALUES(?, ?, ?);');
        mysqli_stmt_bind_param($stmt, 'isi', $klantnr, $token, $ip);
        mysqli_execute($stmt);
        if (mysqli_error($link)) {
            return "Error: " . mysqli_error($link);
        }
        return true;
    } else {
        return false;
    }
}

// update het token zodat deze langer geldig is
function updateToken($link) {
    // kijkt of er een geldig token is
    if (validToken($link) == true) {
        $_SESSION["created"] = time();
        return true;
    } else {
        return false;
    }
}

// haalt het klantnr van de ingelogde gebruiker op uit de database
function getKlantnr($link) {
    // kijkt of het token bestaat
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

// haalt het user level van een gebruiker op uit de database
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

// encrypt een wachtwoord met een random salt SHA-512 encryptie
function encryptPassword($password) {
    $size = 60;
    $salt = '$6$rounds=5000$';
    // strtr() convert alle + tekens naar . tekens
    $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
    $hashed = crypt($password, $salt);
    return $hashed;
}

// kijkt of het wachtwoord overeen komt met het wachtwoord in de database
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
            // encrypt het ingevoerde wachtwoord om deze te vergelijken met die in de database
            $password2 = crypt($password, $wachtwoord);
            // kijkt of de wachtwoorden overeen komen
            if ($wachtwoord == $password2) {
                return true;
            } else {
                return false;
            }
        }
    }
}

// maakt een verbinding met de database
function connectDB() {
    $host = "localhost";
    $user = "root";
    $password = "usbw";
    $database = "vvtissue";
    $port = 3307;

    $link = mysqli_connect($host, $user, $password, $database, $port);
    // kijkt of er een error is of niet
    if (mysqli_connect_error()) {
        return "Error: " . mysqli_connect_error();
    } else {
        return $link;
    }
}

// verwijderd een sessie token
function deleteToken($verwijderen, $link) {
    // kijkt of de sessie wel bestaat
    if (isset($_SESSION["token"])) {
        // kijkt of het token verwijderd moet worden of verlopen is
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

// kijkt of een gebruiker met een bepaald level wel toegang heeft tot de pagina of niet
function restrictedPage($level, $link) {
    // kijkt of de gebruiker is ingelogd
    if (validToken($link) == true) {
        // kijkt of de gebruiker het juiste level heeft
        if (userLevel(getKlantnr($link), $link) == $level) {
            if (mysqli_connect_error($link)) {
                return "Error: " . mysqli_connect_error($link);
            } else {
                // update het token zodat deze langer geldig is
                updateToken($link);
                return true;
            }
        } else {
            header('Location: ../index.php');
            return false;
        }
    } else {
        header('Location: ../index.php');
        return false;
    }
}

// verwijderd het token in de database
function deleteDatabaseToken($link) {
    if (!isset($_SESSION["token"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $result = mysqli_query($link, 'SELECT * FROM token WHERE ip = "' . $ip . '";');
        $row = mysqli_fetch_assoc($result);
        // kijkt of er een error is of dat het ip er wel of niet is
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

// kijkt of de gegevens overeen komen met wat er in de database staat en of de link nog niet verlopen is, als het token verlopen is word deze verwijderd
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

// kijkt of een email adres al bestaat in de database
function CheckEmailExists($emailexists, $link) {

    $query = mysqli_query($link, "SELECT email FROM gebruiker WHERE email = '" . $emailexists . "'");
    $rows = mysqli_num_rows($query);

    if ($rows == 0) {
        return true;
    } else {
        return false;
    }
}

// kijkt of een account is geblokkeerd of niet
function accountBlocked($email, $link) {
    $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "' . $email . '";');
    if (mysqli_error($link)) {
        return mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($result);
        $klantnr = $row["klantnr"];
        $result2 = mysqli_query($link, 'SELECT poging FROM geblokkeerd WHERE klantnr = "' . $klantnr . '";');
        $row2 = mysqli_fetch_assoc($result2);

        // als er 5 incorrecte pogingen zijn gedaan om in te loggen is het account geblokkeerd
        if ($row2["poging"] >= 5) {
            return true;
        } else {
            return false;
        }
    }
}

// telt het aantal incorrecte inlog pogingen en stuurt een email na 5 pogingen om het account te onblokkeren
function accountBlockedCount($email, $link) {
    $result = mysqli_query($link, 'SELECT klantnr FROM gebruiker WHERE email = "'.$email.'";');

    if (mysqli_error($link)) {
        return mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($result);
        $klantnr = $row["klantnr"];
        
        // vraagt het aantal pogingen op uit de database
        $result2 = mysqli_query($link, 'SELECT poging FROM geblokkeerd WHERE klantnr = "' . $klantnr . '";');
        $row2 = mysqli_fetch_assoc($result2);
        $rows = mysqli_num_rows($result2);
        print(mysqli_error($link));

        // kijkt of de gebruiker al bestaat in de table of niet en maakt hem aan als dat nodig is anders word zijn aantal pogingen plus een gedaan
        if ($rows == 0) {
            mysqli_query($link, 'INSERT INTO geblokkeerd(klantnr,poging) VALUES("' . $klantnr . '",1)');
        } else {
            mysqli_query($link, 'UPDATE geblokkeerd SET poging=poging+1 WHERE klantnr = "' . $klantnr . '";');


// als het wachtwoord 5 keer fout is ingevoerd word er een token aangemaakt en een email verstuurd naar de eigenaar van het account
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
