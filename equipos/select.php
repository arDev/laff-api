<?php

require "validar.php";

try {
    $filtro = $_GET['filtro'] ?? '';

    $stmt = $conn->prepare("SELECT id as value, nombre as label FROM equipos 
                    where 1=1
                    and ( nombre like :filtro )
                    LIMIT 100
    ");
    $stmt->execute(array('filtro' => '%' . $filtro . '%'));
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rowCount = $stmt->rowCount();


        echo json_encode($equipos);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
