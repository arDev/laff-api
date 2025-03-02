<?php

require "validar.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];

if (isset($id)) {
    try {
        $conn->beginTransaction();
        $sql = "delete from escudos where  equipo_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $sql = "delete from equipospersonas where equipo_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $sql = "delete from equipos where  id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $conn->commit();
        echo json_encode(['msg' => 'Equipo borrado correctamente', "Ok" => true]);
    } catch (PDOException $e) {
        $conn->rollback();
        echo json_encode(['msg' => $e->getMessage()]);
    }
} else {
    echo json_encode(['msg' => 'Datos incompletos']);
}
?>