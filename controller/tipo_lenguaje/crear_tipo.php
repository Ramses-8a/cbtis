<?php
header('Content-Type: application/json');
include_once('../conexion.php');

try {
    // Verificar que se recibió el nombre del lenguaje
    if (!isset($_POST['nom_lenguaje']) || empty(trim($_POST['nom_lenguaje']))) {
        echo json_encode([
            'success' => false,
            'message' => 'El nombre del lenguaje es requerido'
        ]);
        exit;
    }
    
    $nom_lenguaje = trim($_POST['nom_lenguaje']);
    
    // Verificar si ya existe un tipo de lenguaje con ese nombre
    $sql_check = "SELECT COUNT(*) FROM lenguajes WHERE nom_lenguaje = ?";
    $stmt_check = $connect->prepare($sql_check);
    $stmt_check->execute([$nom_lenguaje]);
    
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe un tipo de lenguaje con ese nombre'
        ]);
        exit;
    }
    
    // Insertar el nuevo tipo de lenguaje
    $sql = "INSERT INTO lenguajes (nom_lenguaje, estatus) VALUES (?, 1)";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$nom_lenguaje]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Tipo de lenguaje creado exitosamente'
    ]);
    
} catch (PDOException $e) {
    error_log("Error PDO en crear_tipo.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Exception $e) {
    error_log("Error general en crear_tipo.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>