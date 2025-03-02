<?php

try {

    $id = $_GET['id'] ?? '';

    $stmt = $conn->prepare("SELECT imagen FROM fotos 
                    where 1=1 
                    and  persona_id = :id 
                    ");
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $base64 = "";

    if (count($result)) {
        $base64 = "data:image/jpeg;base64," . base64_decode($result[0]["imagen"]);
    }
    
    //Codigo

    $base64_string = preg_replace('/^data:image\/jpeg;base64,/', '', $base64);
    
    // Decodifica la cadena Base64
    $imagen_binaria = base64_decode($base64_string);
    
    // Verifica si la decodificación fue exitosa
    if ($imagen_binaria === false) {
        return "Error al decodificar la imagen Base64.";
    }

    // Crea una imagen desde los datos binarios decodificados
    $imagen = imagecreatefromstring($imagen_binaria);
    
    if ($imagen === false) {
        return "Error al crear la imagen desde los datos binarios.";
    }
    
    header('Content-Type: image/png');
    // Guarda la imagen en formato PNG
    imagepng($imagen);

    // Verifica si la imagen se guardó correctamente
    if ($resultado === false) {
        return "Error al guardar la imagen como PNG.";
    }

    // Libera la memoria de la imagen
    imagedestroy($imagen);

/*
    $imagen[0] =
        [
            "id" => $id,
            "imagen" => $base64
        ];
    echo json_encode($imagen); */
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
