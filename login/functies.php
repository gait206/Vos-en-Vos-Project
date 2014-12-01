<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function validToken() {
    $link = connectDB();
    // kijkt of gebruiker ingelogd is
    $ip = $_SERVER["REMOTE_ADDR"];
    $result = mysqli_query($link, 'SELECT * FROM token WHERE ip = "' . $ip . '";');
    if (mysqli_error($link)) {
        return "Error: " . mysqli_error($link);
    } else {
        // vergelijkt waarden beide tokens (in database en in sessie)
        $row = mysqli_fetch_assoc($result);
        if (isset($_SESSION["token"])) {
            deleteToken(false);
            if ($_SESSION["token"] == $row["token"]) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

function createToken($email) {
    $ip = $_SERVER["REMOTE_ADDR"];
    $size = 60;
    $random = mcrypt_create_iv($size);
    $salt = '$6$rounds=5000$';
    $salt .= mcrypt_create_iv($size) . "$";
    $token = crypt($random, $salt);

    $_SESSION["token"] = $token;
    $_SESSION["created"] = time();
    $link = connectDB();
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

function userLevel($email) {
    $link = connectDB();
    $result = mysqli_query($link, 'SELECT level FROM gebruiker WHERE email = "' . $email . '";');
    if (mysqli_error($link)) {
        return "Error: " . mysqli_error($link);
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row["level"];
    }
}

function encryptPassword($password) {
    $size = 60;
    $salt = '$6$rounds=5000$';
    $salt .= mcrypt_create_iv($size) . "$";
    $hashed = crypt($password, $salt);
    return $hashed;
}

function verifyPassword($email, $password) {
    $link = connectDB();
    if ($link == true) {
        $result = mysqli_query($link, 'SELECT wachtwoord FROM gebruiker WHERE email = "' . $email . '";');
        if (mysqli_error($link)) {
            return "Error: " . mysqli_error($link);
        } else {
            $row = mysqli_fetch_assoc($result);
            $password2 = crypt($password, $row["wachtwoord"]);
            if ($row["wachtwoord"] == $password2) {
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


    


function deleteToken($verwijderen) {
    if (isset($_SESSION)) {
        if ($verwijderen) {
            unset($_SESSION);
            session_destroy();
            return true;
        } else {
            if (time() - $_SESSION["created"] > 1800) {
                unset($_SESSION);
                session_destroy();
                return true;
            } else {
                return false;
            }
        }
    }
}

function checkConnectDB() {
    if (isset($link)) {
        if(mysqli_get_host_info($link)){
            return true;
        } else {
            return connectDB();
        }
    } else {
        return false;
    }
}
