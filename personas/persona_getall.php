<?php

require "validar.php";

try {
    $filtro = $_GET['filtro'] ?? '';    

    $stmt = $conn->prepare("SELECT 
personas.id,apellido,personas.nombre,nroDoc,fechaNacimiento,direccion,localidad,telefono,carnet,email

,personas.id as value,concat(apellido,', ' ,personas.nombre) as label ,
IFNULL(ep.equipo_id,0) AS valueEquipo, IFNULL(equipos.nombre,'') AS labelEquipo
,personas.username, personas.fechamodif
                            FROM personas 
                            LEFT OUTER JOIN equipospersonas ep
                            ON ep.persona_id = personas.id
                            LEFT OUTER JOIN equipos
                            ON equipos.id = ep.equipo_id
                    where 1=1 
                    and ( apellido like :filtro 
                    or personas.nombre like :filtro 
                    or nroDoc like :filtro
                    or carnet like :filtro
                    )
                    LIMIT 100");
    //$stmt->bindValue(':filtro', '%'.$data['filtro'].'%');
    $stmt->execute(array('filtro' => '%'.$filtro.'%'));
    $pesonas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pesonas);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>