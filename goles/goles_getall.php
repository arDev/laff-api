<?php
try {
    $filtro = $_GET['filtro'] ?? '';    

    $stmt = $conn->prepare("SELECT  
                            goleadores.id, apellido, nombre, goles, DATE_FORMAT(fecha, '%d/%m/%Y') as fecha FROM goleadores 
                            inner join personas
                            on personas.id = goleadores.persona_id
                            where 1=1 
                            LIMIT 100");
    //$stmt->bindValue(':filtro', '%'.$data['filtro'].'%');
    $stmt->execute(array('filtro' => '%'.$filtro.'%'));
    $goleadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($goleadores);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>