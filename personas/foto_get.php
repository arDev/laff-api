<?php

require "validar.php";

try {

    $id = $_GET['id'] ?? '';

    $stmt = $conn->prepare("SELECT imagen FROM fotos 
                    where 1=1 
                    and  persona_id = :id 
                    ");
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $base64 = "";

    $rowCount = $stmt->rowCount();

    if ($rowCount == 1) {
        $base64 = "data:image/jpeg;base64," . base64_decode($result[0]["imagen"]);
    }
    
    $imagen[0] =
        [
            "id" => $id,
            "imagen" => $base64
        ];
    echo json_encode($imagen); 
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
