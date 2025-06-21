<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/conexion.php');

try {
    $stmt = $connect->prepare('SELECT * FROM tipo_recursos ORDER BY nom_tipo ASC');
    $stmt->execute();
    
    $tipo_recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $tipo_recursos
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los tipos de recurso: ' . $e->getMessage()
    ]);
}
?>