<?php

require "validar.php";

$data = json_decode(file_get_contents("php://input"), true);

try {
    $conn->beginTransaction();
    if (isset($data["id"]))
        $id = $data["id"];

    if (isset($data["division"]))
        $division_id = $data["division"];

    if ($division_id == 0)
        $division_id = null;

    if ($id == 0) {
        $sql = "INSERT INTO equipos (nombre,orden,detalles, division_id,username,fechamodif) 
                VALUES 
                (
                :nombre, 
                :orden, 
                :detalles,
                :division_id,
                :username,
                now())";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre', $data["nombre"]);
        $stmt->bindValue(':orden', $data["orden"]);
        $stmt->bindValue(':detalles', $data["detalles"]);
        $stmt->bindValue(':division_id', $division_id);
        $stmt->bindValue(':username', $infoToken->name);
        $stmt->execute();

        $id = $conn->lastInsertId();
    } else {
        $sql = "update equipos set nombre = :nombre
        , orden = :orden
        ,detalles =  :detalles
        ,division_id = :division_id
        where id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $data["id"]);
        $stmt->bindValue(':nombre', $data["nombre"]);
        $stmt->bindValue(':orden', $data["orden"]);
        $stmt->bindValue(':detalles', $data["detalles"]);
        $stmt->bindValue(':division_id', $division_id);

        $stmt->execute();
    }

    foreach ($data['jugadores'] as $jugador) {

        if ($jugador['accion'] == "A" && $jugador['deBase'] == false) {

            $sql = "INSERT INTO historial (equipo_id,persona_id,desde,hasta) VALUE
                    (
                    :equipo_id,
                    :persona_id,
                    now(),
                    null)";   
                         
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':equipo_id', $id);
            $stmt->bindValue(':persona_id', $jugador['id']);
            $stmt->execute();

            $sql = "INSERT INTO equipospersonas (equipo_id,persona_id) 
                VALUES 
                ( :equipo_id, :persona_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':equipo_id', $id);
            $stmt->bindValue(':persona_id', $jugador['id']);
            $stmt->execute();
        }

        if ($jugador['accion'] == "B" && $jugador['deBase'] == true) {

            $sql = "update historial 
                    set hasta = now()
                    where equipo_id = :equipo_id and persona_id = :persona_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':equipo_id', $id);
            $stmt->bindValue(':persona_id', $jugador['id']);

            $stmt->execute();

            $sql = "delete from equipospersonas where equipo_id = :equipo_id and persona_id = :persona_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':equipo_id', $id);
            $stmt->bindValue(':persona_id', $jugador['id']);

            $stmt->execute();
        }
    }


    if (isset($data['dt']) && $data['dt'] > 0) {
        $dtSql = "update equipos set dt_id = :dt_id 
        where id = :id    
        ";
        $dtStmt = $conn->prepare($dtSql);
        $dtStmt->bindValue(':dt_id', $data["dt"]);
        $dtStmt->bindValue(':id', $id);
        $dtStmt->execute();
    }
    if (isset($data['d1']) && $data['d1'] > 0) {
        $d1Sql = "update equipos set del_id = :d1_id 
        where id = :id    
        ";
        $d1Stmt = $conn->prepare($d1Sql);
        $d1Stmt->bindValue(':d1_id', $data["d1"]);
        $d1Stmt->bindValue(':id', $id);
        $d1Stmt->execute();
    }

    if (isset($data['d2']) && $data['d2'] > 0) {
        $d2Sql = "update equipos set del_id2 = :d2_id 
        where id = :id    
        ";
        $d2Stmt = $conn->prepare($d2Sql);
        $d2Stmt->bindValue(':d2_id', $data["d2"]);
        $d2Stmt->bindValue(':id', $id);
        $d2Stmt->execute();
    }

    if (isset($data["escudo"])) {
        if ($data['escudo'] != "") {
            $sqlEsc = "insert into escudos (imagen, equipo_id) values (:imagen, :id)";
            $stmtEsc = $conn->prepare($sqlEsc);
            $stmtEsc->bindValue(':imagen', base64_encode(explode(",", $data["escudo"])[1]));
            $stmtEsc->bindValue(':id', $id);
            $stmtEsc->execute();
        }
        if ($data['escudo'] == "") {
            $sqlEsc = "delete from escudos where equipo_id = :id";
            $stmtEsc = $conn->prepare($sqlEsc);
            $stmtEsc->bindValue(':id', $id);
            $stmtEsc->execute();
        }
    }
    $conn->commit();
    $msg = 'Equipo agregado correctamente';
    if ($data['id'] > 0) {
        $msg = 'Equipo actualizado correctamente';
    }
    echo json_encode(['msg' => $msg]);
} catch (PDOException $e) {
    $conn->rollback();
    echo json_encode(['msg' => $e->getTrace()]);
}
