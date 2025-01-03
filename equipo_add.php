<?php
$data = json_decode(file_get_contents("php://input"), true);

//echo json_encode($data);

try {
    $conn->beginTransaction();
    $id = $data["equipo"]["id"];
    if ($data["equipo"]["id"] == 0) {
        $sql = "INSERT INTO equipos (nombre,orden,detalles) 
                VALUES 
                (
                :nombre, 
                :orden, 
                :detalles)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre', $data["equipo"]["nombre"]);
        $stmt->bindValue(':orden', $data["equipo"]["orden"]);
        $stmt->bindValue(':detalles', $data["equipo"]["detalles"]);

        $stmt->execute();

        $id = $conn->lastInsertId();
    } else {
        $sql = "update equipos set nombre = :nombre
        , orden = :orden
        ,detalles =  :detalles
               where id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $data["equipo"]["id"]);
        $stmt->bindValue(':nombre', $data["equipo"]["nombre"]);
        $stmt->bindValue(':orden', $data["equipo"]["orden"]);
        $stmt->bindValue(':detalles', $data["equipo"]["detalles"]);

        $stmt->execute();
    }

    foreach ($data['jugadores'] as $jugador) {


        if ($jugador['accion'] == "A" && $jugador['deBase'] == false) {

            $sql = "INSERT INTO equipospersonas (equipo_id,persona_id) 
                VALUES 
                (
                :equipo_id, 
                :persona_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':equipo_id', $id);
            $stmt->bindValue(':persona_id', $jugador['id']);

            $stmt->execute();
        }

        if ($jugador['accion'] == "B" && $jugador['deBase'] == true) {

            $sql = "delete from equipospersonas where equipo_id = :equipo_id and persona_id = :persona_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':equipo_id', $id);
            $stmt->bindValue(':persona_id', $jugador['id']);

            $stmt->execute();
        }
    }


    if (isset($data['dt']) && $data['dt']['value'] > 0) {
        $dtSql = "update equipos set dt_id = :dt_id 
        where id = :id    
        ";
        $dtStmt = $conn->prepare($dtSql);
        $dtStmt->bindValue(':dt_id', $data["dt"]["value"]);
        $dtStmt->bindValue(':id', $id);
        $dtStmt->execute();
    }
    if (isset($data['d1']) && $data['d1']['value'] > 0) {
        $d1Sql = "update equipos set del_id = :d1_id 
        where id = :id    
        ";
        $d1Stmt = $conn->prepare($d1Sql);
        $d1Stmt->bindValue(':d1_id', $data["d1"]["value"]);
        $d1Stmt->bindValue(':id', $id);
        $d1Stmt->execute();
    }

    if (isset($data['d2']) && $data['d2']['value'] > 0) {
        $d2Sql = "update equipos set del_id2 = :d2_id 
        where id = :id    
        ";
        $d2Stmt = $conn->prepare($d2Sql);
        $d2Stmt->bindValue(':d2_id', $data["d2"]["value"]);
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
    if ($data['equipo']['id'] > 0) {
        $msg = 'Equipo actualizado correctamente';
    }
    echo json_encode(['success' => $msg]);
} catch (PDOException $e) {
    $conn->rollback();
    echo json_encode(['error' => $e->getMessage()]);
}
