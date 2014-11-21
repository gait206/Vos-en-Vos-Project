<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//
//    Database functies
//
// WIP eerst datbase afmaken
function dbQuery($query, $array) {
    $link = mysqli_connect("localhost", "root", "usbw", "vvtissue", 3306);
    $stmt = mysqli_prepare($link, $query);

    $vartype = "";
    $values = "";
    foreach ($array as $key => $value) {
        if (is_double($value)) {
            $vartype = $vartype . "d";
        }
        if (is_bool($value)) {
            $vartype = $vartype . "b";
        }
        if (is_float($value)) {
            $vartype = $vartype . "f";
        }
        if (is_string($value)) {
            $vartype = $vartype . "s";
        }
        if (is_int($value)) {
            $vartype = $vartype . "i";
        }
        $values = $values . "$" . $key . ", ";
    }
    print($values);
    print($vartype);

    mysqli_stmt_bind_param($stmt, $vartype, $values);
    $execute = mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_fetch($stmt);

    return $result;
}

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

            return setcookie($name, $value, time() + (24 * 60 * 60));
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

function filter_query_generate($switch, array $checkbox) {
    $count = count($checkbox);
    $query = "SELECT * FROM product ";



    switch ($switch) {
        case 0 : $query .= '';
            break;
        case 1 : $query .= 'WHERE prijs BETWEEN 10.00 AND 20.00 ';
            break;
        case 2 : $query .= 'WHERE prijs BETWEEN 21.00 AND 30.00 ';
            break;
        case 3 : $query .= 'WHERE prijs BETWEEN 31.00 AND 50.00 ';
            break;
        case 4 : $query .= 'WHERE prijs BETWEEN 51.00 AND 75.00 ';
            break;
        case 5 : $query .= 'WHERE prijs BETWEEN 76.00 AND 100.00 ';
            break;
        case 6 : $query .= 'WHERE prijs BETWEEN 101.00 AND 200.00 ';
            break;
        case 7 : $query .= 'WHERE prijs > 200.00 ';
            break;
    }
    if ($count == 1) {
        if ($switch == 0) {
            $query .= "WHERE merk = '" . $checkbox["0"] . "' ";
        } else {
            $query .= "AND merk = '" . $checkbox["0"] . "' ";
        }
    } else {
        $countarray = 1;
        foreach ($checkbox as $key => $value) {
            if ($countarray == 1) {
                if ($switch == 0) {
                    $query .= "WHERE merk IN('" . $checkbox[$key] . "',";
                } else {
                    $query .= "AND merk IN('" . $checkbox[$key] . "',";
                }
            } else if ($countarray < $count) {
                $query .= '"' . $checkbox[$key] . '",';
            } else {
                $query .= '"' . $checkbox[$key] . '")';
            }
            $countarray = $countarray + 1;
        }
    }

    return($query);
}

function search_query_generate($search_term, $query) {
    if ($query == 'SELECT * FROM product ') {

        $query .= 'WHERE productnaam LIKE "%' . $search_term . '%" OR 
                        merk LIKE "%' . $search_term . '%" OR 
                        productnr = "' . $search_term . '" OR
                        omschrijving LIKE "%' . $search_term . '%"';

        return($query);
    } else {
        $search_term = mysql_real_escape_string($search_term);
        $query .= 'AND productnaam LIKE "%' . $search_term . '%" OR 
                        merk LIKE "%' . $search_term . '%" OR 
                        productnr = "' . $search_term . '" OR
                        omschrijving LIKE "%' . $search_term . '%"';

        return($query);
    }
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
    if (isset($_GET[$switch])) {
        $switch = $_GET["switch"];
        if ($number == $switch) {
            print(" selected");
        }
    }
    return;
}

function checked($array, $value) {
    if (isset($_GET[$array])) {
        $name = $_GET[$array];
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
