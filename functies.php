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
        case 0 : $query .= 'WHERE categorie = "papier"';
            break;
        case 1 : $query .= 'WHERE categorie = "dispencers"';
            break;
        case 2 : $query .= 'WHERE categorie = "reinigingsmiddelen"';
            break;
        case 3 : $query .= 'WHERE categorie = "schoonmaakmaterialen"';
            break;
        case 4 : $query .= 'WHERE categorie IN ("papier","dispencers","reinigingsmiddelen","schoonmaakmateialen")';
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
        $query .= "AND merk = '" . $checkbox["0"] . "' ";
    } else {
        $countarray = 1;
        foreach ($checkbox as $key => $value) {
            if ($countarray == 1) {
                $query .= "AND merk IN('" . $checkbox[$key] . "',";
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
    $query .= 'AND productnaam LIKE "%' . $search_term . '%" OR
                        productnr = "' . $search_term . '" OR
                        omschrijving LIKE "%' . $search_term . '%"';

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
    }
    return($query);
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

function checked($array, $value) {
    if (isset($_POST[$array])) {
        $name = $_POST[$array];
        foreach ($array as $key => $value) {
            if ($key == $value) {
                print(" checked");
            }
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

// maakt geen nieuw token aan wss
function createToken($email, $link) {
    $ip = $_SERVER["REMOTE_ADDR"];
    $size = 60;
    $random = $_SERVER['HTTP_USER_AGENT'];
    $salt = '$6$rounds=5000$';
    $salt .= strtr(base64_encode(mcrypt_create_iv($size)), '+', '.') . "$";
    $token = crypt($random, $salt);

    $_SESSION["token"] = $token;
    $_SESSION["created"] = time();
    if ($link == true) {

        mysqli_query($link, 'INSERT INTO token VALUES("' . $email . '", "' . $token . '", "' . $ip . '");');
        if (mysqli_error($link)) {
            return "Error: " . mysqli_error($link);
        }
        return true;
    } else {
        return false;
    }
}

function updateToken($email, $link) {
    if (validToken($link) == true) {
        $_SESSION["created"] = time();
        return true;
    } else {
        return false;
    }
}

function getEmail($link) {
    if (isset($_SESSION["token"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $token = $_SESSION["token"];
        $stmt = mysqli_prepare($link, 'SELECT email FROM token WHERE ip = ? AND token = ?;');
        mysqli_stmt_bind_param($stmt, 'ss', $ip, $token);
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $email);
        if (mysqli_stmt_error($stmt)) {
            return "Error: " . mysqli_stmt_error($stmt);
        } else {
            mysqli_stmt_fetch($stmt);
            return $email;
        }
    } else {
        return false;
    }
}

function userLevel($email, $link) {
    $stmt = mysqli_prepare($link, 'SELECT level FROM gebruiker WHERE email = ?;');
    mysqli_stmt_bind_param($stmt, 's', $email);
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
            $email = getEmail($link);
            $stmt = mysqli_prepare($link, 'DELETE FROM token WHERE email = ?;');
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_execute($stmt);
            unset($_SESSION);
            session_destroy();
        }
    }
}

function restrictedPage($level, $link) {
    if (validToken($link) == true) {
        if (userLevel(getEmail($link), $link) == $level) {
            if (mysqli_connect_error($link)) {
                return "Error: " . mysqli_connect_error($link);
            } else {
                updateToken(getEmail($link), $link);
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

function verifyPasswordForgot($email, $token, $link) {
    $stmt = mysqli_prepare($link, 'SELECT token FROM recovery WHERE email = ?;');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $token2);

    if ($token == $token2) {
        return true;
    } else {
        return false;
    }
}
function prijsformat($prijs){
	return number_format($prijs,2,",",".");
}

function prijsber($prijs) {
    return number_format($prijs * 1.21, 2, ",", ".");
}

// Validatiecontrole emailadres
function validate_email($email, $strict = true) {
    $dot_string = $strict ?
            '(?:[A-Za-z0-9!#$%&*+=?^_`{|}~\'\\/-]|(?<!\\.|\\A)\\.(?!\\.|@))' :
            '(?:[A-Za-z0-9!#$%&*+=?^_`{|}~\'\\/.-])'
    ;
    $quoted_string = '(?:\\\\\\\\|\\\\"|\\\\?[A-Za-z0-9!#$%&*+=?^_`{|}~()<>[\\]:;@,. \'\\/-])';
    $ipv4_part = '(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])';
    $ipv6_part = '(?:[A-fa-f0-9]{1,4})';
    $fqdn_part = '(?:[A-Za-z](?:[A-Za-z0-9-]{0,61}?[A-Za-z0-9])?)';
    $ipv4 = "(?:(?:{$ipv4_part}\\.){3}{$ipv4_part})";
    $ipv6 = '(?:' .
            "(?:(?:{$ipv6_part}:){7}(?:{$ipv6_part}|:))" . '|' .
            "(?:(?:{$ipv6_part}:){6}(?::{$ipv6_part}|:{$ipv4}|:))" . '|' .
            "(?:(?:{$ipv6_part}:){5}(?:(?::{$ipv6_part}){1,2}|:{$ipv4}|:))" . '|' .
            "(?:(?:{$ipv6_part}:){4}(?:(?::{$ipv6_part}){1,3}|(?::{$ipv6_part})?:{$ipv4}|:))" . '|' .
            "(?:(?:{$ipv6_part}:){3}(?:(?::{$ipv6_part}){1,4}|(?::{$ipv6_part}){0,2}:{$ipv4}|:))" . '|' .
            "(?:(?:{$ipv6_part}:){2}(?:(?::{$ipv6_part}){1,5}|(?::{$ipv6_part}){0,3}:{$ipv4}|:))" . '|' .
            "(?:(?:{$ipv6_part}:){1}(?:(?::{$ipv6_part}){1,6}|(?::{$ipv6_part}){0,4}:{$ipv4}|:))" . '|' .
            "(?::(?:(?::{$ipv6_part}){1,7}|(?::{$ipv6_part}){0,5}:{$ipv4}|:))" .
            ')';
    $fqdn = "(?:(?:{$fqdn_part}\\.)+?{$fqdn_part})";
    $local = "({$dot_string}++|(\"){$quoted_string}++\")";
    $domain = "({$fqdn}|\\[{$ipv4}]|\\[{$ipv6}]|\\[{$fqdn}])";
    $pattern = "/\\A{$local}@{$domain}\\z/";
    return preg_match($pattern, $email, $matches) &&
            (
            !empty($matches[2]) && !isset($matches[1][66]) && !isset($matches[0][256]) ||
            !isset($matches[1][64]) && !isset($matches[0][254])
            )
    ;
}

// Controleren voor geldig BTW nummer
// een geldig btw nummer is: NL 1535.50.909.B02

function isBTW($psVatInput) {
    $psVatInput = trim($psVatInput);
    $psVatInput = str_replace('.', '', $psVatInput);
    $aVatMatch = array();

    if (!preg_match('/^([a-z]{2})[ ]*(.+)$/is', $psVatInput, $aVatMatch)) {
        return false;
    }

    $aVatMatch[1] = strtoupper($aVatMatch[1]);
    $aVatRegexes = array(
        'AT' => 'U[0-9]{8}',
        'BE' => '0[0-9]{9}',
        'BG' => '[0-9]{9,10}',
        'CY' => '[0-9]{8}[A-Za-z]',
        'CZ' => '[0-9]{8,10}',
        'DE' => '[0-9]{9}',
        'DK' => '[0-9]{2} ?[0-9]{2} ?[0-9]{2} ?[0-9]{2}',
        'EE' => '[0-9]{9}',
        'EL' => '[0-9]{9}',
        'ES' => '([A-Za-z0-9][0-9]{7}[A-Za-z0-9])',
        'FI' => '[0-9]{8}',
        'FR' => '[A-Za-z0-9]{2} ?[0-9]{9}',
        'GB' => '([0-9]{3} ?[0-9]{4} ?[0-9]{2}|[0-9]{3} ?[0-9]{4} ?[0-9]{2} ?[0-9]{3}|GD[0-9]{3}|HA[0-9]{3})',
        'HU' => '[0-8]{8}',
        'IE' => '[0-9][A-Za-z0-9+*][0-9]{5}[A-Za-z]',
        'IT' => '[0-9]{11}',
        'LT' => '([0-9]{9}|[0-9]{12})',
        'LU' => '[0-9]{8}',
        'LV' => '[0-9]{11}',
        'MT' => '[0-9]{8}',
        'NL' => '[0-9]{9}B[0-9]{2}',
        'PL' => '[0-9]{10}',
        'PT' => '[0-9]{9}',
        'RO' => '[0-9]{2,10}',
        'SE' => '[0-9]{12}',
        'SI' => '[0-9]{8}',
        'SK' => '[0-9]{10}',
    );
}
