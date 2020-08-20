<?php

function dbObj()
{
    $conn = new mysqli('127.0.0.1', "root", "go2urmail", "biopulse");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function apiHeaders()
{
    header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, X-User-Token');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/json");
}

function isLoggedIn()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_key'])) {
        header("location: index.php");
    }
}

function logout()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    header("location: index.php");
}

function generateRandomString($length = 10)
{
    $characters = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function isValidRequiredField($field)
{
    return strlen($field) > 0;
}

function array_keys_exists(array $keys, array $arr)
{
    return !array_diff_key(array_flip($keys), $arr);
}

function sanitize_item($item)
{
    return htmlentities(remove_empty_p(trim($item)));
}

function formatFieldsForError(array $fields)
{
    return implode(", ", array_map(function ($str) {
        return ucwords(str_replace("_", " ", $str));
    }, $fields));
}

function remove_empty_p($content)
{
    // clean up p tags around block elements
    $content = preg_replace(array(
        '#<p>\s*<(div|aside|section|article|header|footer)#',
        '#</(div|aside|section|article|header|footer)>\s*</p>#',
        '#</(div|aside|section|article|header|footer)>\s*<br ?/?>#',
        '#<(div|aside|section|article|header|footer)(.*?)>\s*</p>#',
        '#<p>\s*</(div|aside|section|article|header|footer)#',
    ), array(
        '<$1',
        '</$1>',
        '</$1>',
        '<$1$2>',
        '</$1',
    ), $content);
    return preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
}

