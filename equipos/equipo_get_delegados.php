<?php

//require "validar.php";

try {
    $id = $_GET['id'] ?? '';


    $stmt = $conn->prepare("SELECT 'dt' as cargo,personas.id as value
                                    ,concat(personas.apellido,', ',personas.nombre) as label
                                    ,personas.nroDoc FROM equipos
                            inner join personas
                            on equipos.dt_id = personas.id
                            WHERE equipos.id = :id 
                            UNION all
                            SELECT 'del' as cargo,personas.id,concat(personas.apellido,', ',personas.nombre) as label,personas.nroDoc FROM equipos
                            inner join personas
                            on equipos.del_id = personas.id
                            WHERE equipos.id = :id 
                            UNION all
                            SELECT 'del2' as cargo,personas.id,concat(personas.apellido,', ',personas.nombre) as label,personas.nroDoc FROM equipos
                            inner join personas
                            on equipos.del_id2 = personas.id
                            WHERE equipos.id = :id 
                            union all
                            SELECT 'div' as cargo,ifnull(division_id,0),'' as label, 0 FROM equipos
                            WHERE equipos.id = :id
                            ");
    $stmt->execute(array('id' => $id));
    $delegados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);

    echo json_encode($delegados);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>