<?php
header('Content-Type: application/json');
include_once('../conexion.php');

try {
    // Verificar que se recibió el ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID del tipo de lenguaje es requerido'
        ]);
        exit;
    }
    
    $id = $_POST['id'];
    
    // Verificar si el tipo de lenguaje existe
    $sql_check = "SELECT nom_lenguaje FROM lenguajes WHERE pk_lenguaje = ?";
    $stmt_check = $connect->prepare($sql_check);
    $stmt_check->execute([$id]);
    
    if ($stmt_check->rowCount() == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'El tipo de lenguaje no existe'
        ]);
        exit;
    }
    
    // Eliminar el tipo de lenguaje
    $sql = "DELETE FROM lenguajes WHERE pk_lenguaje = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Tipo de lenguaje eliminado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo eliminar el tipo de lenguaje'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Error PDO en eliminar_tipo.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Exception $e) {
    error_log("Error general en eliminar_tipo.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>