<?php
$data = json_decode(file_get_contents("php://input"), true);

//echo json_encode($data);

try {
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
    } else
    {
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

    if (isset($data["escudo"]) && $data['escudo'] != "") {
        $sqlEsc = "insert into escudos (imagen, equipo_id) values (:imagen, :id)";
        $stmtEsc = $conn->prepare($sqlEsc);
        $stmtEsc->bindValue(':imagen', base64_encode(explode(",", $data["escudo"])[1]));
        $stmtEsc->bindValue(':id', $id);
        $stmtEsc->execute();
    }

    echo json_encode(['success' => 'Equipo actualizado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
