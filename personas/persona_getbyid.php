<?php

require "validar.php";

try {
    $id = $_GET['id'] ?? '';    

    $stmt = $conn->prepare("SELECT * FROM personas 
                    where 1=1 
                    and id = :id 
                    ");

    $stmt->execute(array('id' => $id));
    $pesonas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pesonas);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>