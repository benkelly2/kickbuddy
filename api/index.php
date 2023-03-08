<?php

/**
 * API for KickBuddy web app.
 * 
 * @author Ben Kelly w19014367
 */
/** Header to set the page type to JSON */
header("Content-Type: application/json; charset=UTF-8");
/** Header to allow access to all types of devices */
header("Access-Control-Allow-Origin: *");
/** Header to allow use of Authentication headers */
header("Access-Control-Allow-Headers: GET, POST");

/** Autoloader */
include 'config/autoloader.php';
spl_autoload_register('autoloader');

/** Define secret key */
define('SECRET', 'dU7jGF6Er&uJ4Zz');

/** Storing URL path into variables */
$url = $_SERVER["REQUEST_URI"];
$path = parse_url($url)['path'];
$path = str_replace("kickbuddy/api/", "", $path);

/**
 * Switch statement to direct client to correct endpoint
 */
try {
    switch ($path) {
        case '/':
            $endpoint = new Base(); 
            break;
        case '/play':
            $endpoint = new Play();
            break;
        case '/login':
            $endpoint = new Login();
            break;
        case '/profile':
            $endpoint = new Profile();
            break;
        case '/signup':
            $endpoint = new Signup();
            break;
        default:
            $endpoint = new ClientError("Path not found: ".$path, 404);
    }
} catch(ClientErrorException $e) {
    $endpoint = new ClientError($e->getMessage(), $e->getCode());
}

$response = $endpoint->getData();
echo json_encode($response);