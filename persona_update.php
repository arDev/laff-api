<?php
// endpoints/persona_update.php

$data = json_decode(file_get_contents("php://input"), true);
$campos = "";
$variables = "";

foreach($data as $attr=>$value)
{
    if($attr != "id")
    {
       $campos = $campos.$attr." = :$attr,";
    }
}

$campos = rtrim($campos,",");


if (isset($data['nombre'])) {
    try {
        $sql = "update personas set $campos
                where  id = :id";
        $stmt = $conn->prepare($sql);
       
        foreach($data as $attr=>$value)
        {       
            $stmt->bindValue(':'.$attr, $value);
        }
        
        $stmt->execute();
        echo json_encode(['success' => 'Usuario agregado correctamente']);
        
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Datos incompletos']);
}

?>