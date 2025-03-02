<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

header("Content-Type: application/json");


require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = $_GET['request'] ?? '';

// Rutas
switch ($request) {
    case 'login':
        if ($method == 'POST') {
            require 'login/index.php';
        }
        break;
    case 'persona':
        if ($method == 'GET') {
            require 'personas/persona_getall.php';
        } elseif ($method == 'POST') {
            require 'personas/persona_add.php';
        } elseif ($method == 'PUT') {
            require 'personas/persona_update.php';
        } elseif ($method == 'DELETE') {
            require 'personas/persona_delete.php';
        } else {
            echo json_encode(['error' => 'Método no permitido']);
        }
        break;

    case "equipo":
        if ($method == 'GET') {
            require 'equipos/equipo_getall.php';
        }
        if ($method == 'POST') {
            require 'equipos/equipo_add.php';
        }
        if ($method == 'DELETE') {
            require 'equipos/equipo_delete.php';
        }
        break;

    case "equiposselect":
        if ($method == 'GET') {
            require 'equipos/select.php';
        }
        break;


    case 'persona_sinequipo':
        if ($method == 'GET') {
            require 'personas/persona_get_sinequipo.php';
        }
        break;

    case "persona_getbyid":
        if ($method == 'GET') {
            require 'personas/persona_getbyid.php';
        }
        break;
    case "equipo_get_delegados":
        require 'equipos/equipo_get_delegados.php';
        break;
    case "equipo_get_jugadores":
        require 'equipos/equipo_get_jugadores.php';
        break;
    case "escudo":
        if ($method == 'GET') {
            require 'equipos/escudo_get.php';
        }
        break;

    case "foto":
        if ($method == 'GET') {
            require 'personas/foto_get.php';
        }
        break;
    case "carnet":
        if ($method == 'GET') {
            require 'personas/foto_carnet.php';
        }
        break;

    case "goles":
        if ($method == 'GET') {
            require 'goles/goles_getall.php';
        }
        if ($method == 'POST') {
            require 'goles/goles_add.php';
        }
        if ($method == 'DELETE') {
            require 'goles/goles_delete.php';
        }
        break;
    case "tabla":
        if ($method == 'GET') {
            require 'goles/tabla_get.php';
        }
        break;

    case "divisiones":
        if ($method == 'GET') {
            require 'divisiones/divisiones_get.php';
        }
        break;

        case "historial":
            if ($method == 'GET') {
                require 'personas/historial_get.php';
            }
            break;

            case "CambiarPass":
                if ($method == 'POST') {
                    require 'cambiar_pass.php';
                }
                break;
            

    default:
        echo json_encode(['error' => 'Endpoint no encontrado']);
        break;
}
