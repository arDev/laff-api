<?php

try {
    $id = $_GET['id'] ?? '';


    $stmt = $conn->prepare("select personas.*,1 as deBase, '' as accion from equipospersonas
                            inner join personas
                            on personas.id = equipospersonas.persona_id
                            where equipo_id = :id
                            ");
    $stmt->execute(array('id' => $id));
    $delegados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($delegados);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>