<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

header("Content-Type: application/json");

// Incluye el archivo de configuración
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = $_GET['request'] ?? '';

// Define las rutas y maneja las solicitudes
switch ($request) {
    case 'persona':
        if ($method == 'GET') {
            require 'persona_getall.php';
        } elseif ($method == 'POST') {
            require 'persona_add.php';
        } elseif ($method == 'PUT') {
            require 'persona_update.php';
        } else {
            echo json_encode(['error' => 'Método no permitido']);
        }
        break;
    case "persona_getbyid":
        if ($method == 'GET') {
            require 'persona_getbyid.php';
        }
        break;

    case 'delete_persona':
        if ($method == 'DELETE') {
            echo "DELETE";
            require 'persona_delete.php';
        } else {
            echo json_encode(['error' => 'Método no permitido']);
        }
        break;

    case "equipo":
        if ($method == 'GET') {
            require 'equipo_getall.php';
        }
        if ($method == 'POST') {
            require 'equipo_add.php';
        }
        break;
    case "equipo_get_delegados":
        require 'equipo_get_delegados.php';
        break;
    case "equipo_get_jugadores":
        require 'equipo_get_jugadores.php';
        break;
    case "escudo":
        if ($method == 'GET') {
            require 'escudo_get.php';
        }
        break;

    default:
        echo json_encode(['error' => 'Endpoint no encontrado']);
        break;
}
