<?php 
require "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    $headers = getallheaders();   
    if (!isset($headers["authorization"])) {
        http_response_code(401);
        echo json_encode(["msg" => "Token requerido"]);
        exit;
    } 

    $jwt = str_replace("Bearer ", "", $headers["authorization"]);
    $key = "laff_super_seguro";

    $infoToken = JWT::decode($jwt, new Key($key, 'HS256'));
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["msg" => "Token inválido: " . $e->getMessage()]);
    exit();
}
?>