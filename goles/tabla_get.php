<?php
try {

    $stmt = $conn->prepare("SELECT  
                            goleadores.persona_id,apellido, personas.nombre, equipos.nombre as equipo, sum(goles) as goles FROM goleadores 
                            inner join personas
                            on personas.id = goleadores.persona_id
                            LEFT outer JOIN equipospersonas rel
                            ON rel.persona_id = personas.id
	   								LEFT outer JOIN  equipos
   									ON equipos.id = rel.equipo_id
                            where 1=1 
                            group by goleadores.persona_id,apellido, nombre
                            ORDER BY sum(goles) desc
                            LIMIT 100");
    $stmt->execute();
    $goleadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($goleadores);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>