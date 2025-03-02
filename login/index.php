<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/AuthController.php';
require __DIR__ . '/Auth.php';

use Src\Controllers\AuthController;

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/laff/api.php?request=login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $response = $authController->login($username, $password);
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = getBearerToken() ?? '';

    $response = $authController->validateToken($token);
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint no encontrado']);
}


function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

?>