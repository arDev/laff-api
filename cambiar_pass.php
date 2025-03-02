<?php

require "validar.php";

$newPass = json_decode(file_get_contents("php://input"), true);

if (isset($newPass['pass']) && isset($newPass['newPass'])) {
    try {
        $conn->begintransaction();

        $sql = " update usuarios 
        set password = :newPass
        where username = :username
        and password = :old
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $infoToken->name);
        $stmt->bindValue(':old', $newPass['pass']);
        $stmt->bindValue(':newPass', $newPass['newPass']);

        $stmt->execute();

        if ($stmt->rowCount() != 1)
            throw new Exception("Contraseña actual incorrecta!");

        http_response_code(200);
        $conn->commit();
        echo json_encode(['msg' => 'Ha cambiado su contraseña!']);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['msg' => $e->getMessage()]);
    }
} else {
    echo json_encode(['msg' => "No se pudo cambiar la contraseña!"]);
}
