<?php

require "validar.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];

if (isset($id)) {
    try {
        $sql = "delete from personas 
                where  id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $data['id']);
        
        $stmt->execute();

        echo json_encode(['msg' => 'Persona borrada correctamente', "Ok" => true]);
    } catch (PDOException $e) {
        echo json_encode(['msg' => $e->getMessage()]);
    }
} else {
    echo json_encode(['msg' => 'Datos incompletos']);
}
?>