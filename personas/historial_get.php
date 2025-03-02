<?php
require "validar.php";

try {

    $id = $_GET['id'] ?? '';    

    $stmt = $conn->prepare("SELECT 
                            h.id,
                            e.nombre,desde,hasta 
                            FROM historial h
                            INNER JOIN equipos e
                            ON e.id = h.equipo_id
                            WHERE persona_id = :id
                            ORDER BY h.id asc
                    ");
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $base64 = "";

    echo json_encode($result);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>