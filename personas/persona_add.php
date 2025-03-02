<?php

require "validar.php";

$persona = json_decode(file_get_contents("php://input"), true);

$carnet = obtenerProximoCarnet($conn);

if (isset($persona['nombre'])) {
    try {
        $conn->begintransaction();

        $sql = "INSERT INTO personas (
                apellido
                ,nombre 
                ,nroDoc
                ,fechaNacimiento
                ,carnet
                ,direccion
                ,localidad
                ,telefono
                ,username
                ,fechamodif
        ) VALUES (
        :apellido
        ,:nombre
        ,:nroDoc
        ,:fechaNacimiento
        ,:carnet
        ,:direccion
        ,:localidad
        ,:telefono
        ,:username
        ,now()
        )";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':apellido', $persona['apellido']);
        $stmt->bindValue(':nombre', $persona['nombre']);
        $stmt->bindValue(':nroDoc', $persona['nroDoc']);
        $stmt->bindValue(':fechaNacimiento', $persona['fechaNacimiento']);
        $stmt->bindValue(':carnet', $carnet);
        $stmt->bindValue(':direccion', $persona['direccion']);
        $stmt->bindValue(':localidad', $persona['localidad']);
        $stmt->bindValue(':telefono', $persona['telefono']);
        $stmt->bindValue(':username', $infoToken->name);

        $stmt->execute();

        $id = $conn->lastInsertId();

        $foto = $persona["foto"];

        if (isset($foto)) {
            if ($foto != "" && $foto != "/src/assets/sinImagen.jpg") {
                $sqlEsc = "insert into fotos (imagen, persona_id) values (:imagen, :id)";
                $stmtEsc = $conn->prepare($sqlEsc);
                $stmtEsc->bindValue(':imagen', base64_encode(explode(",", $foto)[1]));
                $stmtEsc->bindValue(':id', $id);
                $stmtEsc->execute();
            }
        }

        $equipo_id = $persona["equipo_id"];
        if (isset($equipo_id) && $equipo_id > 0) {
            $sql = "insert into equipospersonas (equipo_id,persona_id) values
            (:equipo_id, :persona_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':equipo_id', $equipo_id);
            $stmt->bindValue(':persona_id', $id);
            $stmt->execute();

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
        }


        $conn->commit();
        echo json_encode(['msg' => 'Jugador agregado correctamente']);
    } catch (PDOException $e) {
        $conn->rollback();
        echo json_encode(['msg' => $e->getTrace()]);
    }
} else {
    echo json_encode(['msg' => 'Datos incompletos']);
}


function obtenerProximoCarnet($conn)
{

    $sql = "SELECT 
                a.carnet + 1 AS libre
                FROM
                personas AS a
                LEFT JOIN personas AS b 
                ON a.carnet + 1  = b.carnet
                WHERE
                b.carnet IS NULL AND a.carnet > 0
                LIMIT 1
                ;";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row[0]['libre'];
    } else {
        return 1;
    }
}
