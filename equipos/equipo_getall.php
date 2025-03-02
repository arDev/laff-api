<?php

require "validar.php";

try {
    $filtro = $_GET['filtro'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM equipos 
                    where 1=1
                    and ( nombre like :filtro )
                    LIMIT 100
    ");
    $stmt->execute(array('filtro' => '%' . $filtro . '%'));
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rowCount = $stmt->rowCount();
    http_response_code(200);
    echo json_encode($equipos);

} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
