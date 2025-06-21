<?php
header('Content-Type: application/json');
include_once('../conexion.php');

try {
    // Consulta para obtener todos los tipos de lenguaje
    $sql = "SELECT pk_lenguaje, nom_lenguaje, estatus FROM lenguajes ORDER BY nom_lenguaje ASC";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    
    $tipos_lenguaje = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $tipos_lenguaje
    ]);
    
} catch (PDOException $e) {
    error_log("Error en mostrar_tipos.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los tipos de lenguaje: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error general en mostrar_tipos.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error interno del servidor',
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>