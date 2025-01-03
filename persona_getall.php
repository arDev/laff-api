<?php
try {
    $filtro = $_GET['filtro'] ?? '';    

    $stmt = $conn->prepare("SELECT *,id as value,concat(apellido,', ' ,nombre) as label FROM personas 
                    where 1=1 
                    and ( apellido like :filtro 
                    or nombre like :filtro )
                    LIMIT 100");
    //$stmt->bindValue(':filtro', '%'.$data['filtro'].'%');
    $stmt->execute(array('filtro' => '%'.$filtro.'%'));
    $pesonas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pesonas);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>