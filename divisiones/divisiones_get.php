<?php
try {

    $stmt = $conn->prepare("SELECT  
                            * from divisiones");
    $stmt->execute();
    $divisiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($divisiones);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>