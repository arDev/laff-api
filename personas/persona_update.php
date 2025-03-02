<?php

require "validar.php";

$persona = json_decode(file_get_contents("php://input"), true);

$id = $persona['id'];
http_response_code(200);
if (isset($persona['nombre']) && $id > 0) {
    try {
        $conn->beginTransaction();

        $sql = "update personas set 
                apellido = :apellido,
                nombre = :nombre,
                direccion = :direccion,
                localidad = :localidad,
                telefono = :telefono,
                email = :email,
                username = :username,
                fechamodif = now()
                where  id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $persona['id']);

        $stmt->bindValue(':apellido', $persona['apellido']);
        $stmt->bindValue(':nombre', $persona['nombre']);
        $stmt->bindValue(':direccion', $persona['direccion']);
        $stmt->bindValue(':localidad', $persona['localidad']);
        $stmt->bindValue(':telefono', $persona['telefono']);
        $stmt->bindValue(':email', $persona['email']);
        $stmt->bindValue(':username', $infoToken->name);
        $stmt->execute();

        if (isset($foto)) {
            CargarFoto($persona, $id, $conn);
        }

        CambiarEquipo($persona, $conn);

        $conn->commit();

        echo json_encode(['msg' => 'Jugador actualizado correctamente']);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['msg' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['msg' => 'Datos incompletos']);
}

function CambiarEquipo($persona, &$conn)
{
    $id = $persona['id'];
    $equipo_id = $persona["equipo_id"];

    if (!ValidarEquipoDestino($equipo_id, $conn))
        throw new Exception("No se pueden agregar mas jugadores al equipo seleccionado!");

    $sql = "delete from equipospersonas
        WHERE persona_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();


    if (isset($equipo_id)) {
        $sql = "UPDATE historial 
                        SET hasta = NOW()
                        WHERE persona_id = :id
                        AND hasta is null";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() != 0 && $stmt->rowCount() != 1)
            throw new Exception("No se puede actualizar el historial");
    }

    if (isset($equipo_id) && $equipo_id > 0) {
        $sql = "INSERT INTO historial (equipo_id,persona_id,desde,hasta) VALUE
                    (
                    :equipo_id,
                    :persona_id,
                    now(),
                    null)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':equipo_id', $equipo_id);
        $stmt->bindValue(':persona_id', $id);
        $stmt->execute();

        if ($stmt->rowCount() != 1)
            throw new Exception("No se puede actualizar el historial");

        $sql = "insert into equipospersonas (equipo_id,persona_id) values
        (:equipo_id, :persona_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':equipo_id', $equipo_id);
        $stmt->bindValue(':persona_id', $id);
        $stmt->execute();
    }
}

function ValidarEquipoDestino($equipo_id, &$conn)
{
    $validar = true;
    $sql = "SELECT equipo_id FROM equipospersonas WHERE equipo_id = :equipo_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":equipo_id", $equipo_id);
    $stmt->execute();

    $filas = $stmt->fetchAll();

    if (count($filas) > 20)
        $validar = false;

    return $validar;
}

function CargarFoto($persona, PDO &$conn)
{
    $id = $persona['id'];
    $foto = $persona["foto"];

    if ($foto != "" && $foto != "/src/assets/sinImagen.jpg") {
        $sql = "delete from fotos where persona_id = :id";
        $stmtEsc = $conn->prepare($sql);
        $stmtEsc->bindValue(':id', $id);
        $stmtEsc->execute();

        $sql = "insert into fotos (imagen, persona_id) values (:imagen, :id)";
        $stmtEsc = $conn->prepare($sql);
        $stmtEsc->bindValue(':imagen', base64_encode(explode(",", $foto)[1]));
        $stmtEsc->bindValue(':id', $id);
        $stmtEsc->execute();
    }
    if ($foto == "") {
        $sql = "delete from fotos where persona_id = :id";
        $stmtEsc = $conn->prepare($sql);
        $stmtEsc->bindValue(':id', $id);
        $stmtEsc->execute();
    }
}
