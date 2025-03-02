<?php

//require "validar.php";

try {
    $id = $_GET['id'] ?? '';
    $lista = $_GET['lista'] ?? '';


    $stmt = $conn->prepare("select personas.*,1 as deBase, '' as accion from equipospersonas
                            inner join personas
                            on personas.id = equipospersonas.persona_id
                            where equipo_id = :id
                            ORDER BY concat(apellido,', ',nombre) asc
                            ");
    $stmt->execute(array('id' => $id));
    $delegados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*
    if ($lista != '') {
        $cuantos = 37 - count($delegados);

        for ($i = 0; $i < $cuantos; $i++) {
            array_push($delegados, "");
        }
    }
    */

    echo json_encode($delegados);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
