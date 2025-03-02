<?php
// endpoints/persona_add.php

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['persona_id'])) {
    try {
        $sql = "INSERT INTO goleadores (
                persona_id
                ,equipo_id
                ,goles
                ,fecha
        ) VALUES (
        :persona_id
        ,:equipo_id
        ,:goles
        ,:fecha
        )";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':persona_id', $data['persona_id']);
        $stmt->bindValue(':equipo_id', 54); //$data['equipo_id']
        $stmt->bindValue(':goles', $data['goles']);
        $stmt->bindValue(':fecha', $data['fecha']);

        $stmt->execute();

        echo json_encode(['success' => 'Gol agregado correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Datos incompletos']);
}

?>