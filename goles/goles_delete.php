<?php

$id =$_GET['id'];

if (isset($id)) {
    try {
        $sql = "delete from goleadores 
                where  id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id);
        
        $stmt->execute();

        echo json_encode(['msg' => 'Gol borrado correctamente', "Ok" => true]);
    } catch (PDOException $e) {
        echo json_encode(['msg' => $e->getMessage()]);
    }
} else {
    echo json_encode(['msg' => 'Datos incompletos']);
}
?>