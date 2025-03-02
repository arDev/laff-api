<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

header("Content-Type: application/json");

require "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include("config.php");

// Recibir el JSON desde el cuerpo de la petición
$json = file_get_contents("php://input");

// Decodificar JSON a un array asociativo
$data = json_decode($json, true);

$username = $data['user'];
$password = $data['pass'];

$sql = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password' ";

$result = $conn->query($sql);

try {
    if ($result->rowCount() == 1) {
        $row = $result->fetch(PDO::FETCH_ASSOC);

        $key = "laff_super_seguro"; // Clave secreta (¡cámbiala!)
        $payload = [
            "sub" => 123, // ID del usuario
            "name" => $username,
            "iat" => time(), // Tiempo de emisión
            "exp" => time() + (60 * 180) // Expira en 3 horas
            //,"custom_data" => [  // Puedes agregar objetos o arrays
            //"usuario" => "Admin"
            //]
        ];

        // Generar el token
        $jwt = JWT::encode($payload, $key, 'HS256');

        echo json_encode(["token" => $jwt, "nombre" => $username]);
    } else
        echo json_encode(['token' => null]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
