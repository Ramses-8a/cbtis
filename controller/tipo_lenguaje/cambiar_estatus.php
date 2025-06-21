<?php
header('Content-Type: application/json');
include_once('../conexion.php');

try {
    // Verificar que se recibieron los datos necesarios
    if (!isset($_POST['id']) || !isset($_POST['nuevo_estatus'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID y nuevo estatus son requeridos'
        ]);
        exit;
    }
    
    $id = $_POST['id'];
    $nuevo_estatus = $_POST['nuevo_estatus'];
    
    // Validar que el estatus sea 0 o 1
    if ($nuevo_estatus != 0 && $nuevo_estatus != 1) {
        echo json_encode([
            'success' => false,
            'message' => 'El estatus debe ser 0 (inactivo) o 1 (activo)'
        ]);
        exit;
    }
    
    // Actualizar el estatus del tipo de lenguaje
    $sql = "UPDATE lenguajes SET estatus = ? WHERE pk_lenguaje = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$nuevo_estatus, $id]);
    
    if ($stmt->rowCount() > 0) {
        $accion = $nuevo_estatus == 1 ? 'activado' : 'desactivado';
        echo json_encode([
            'success' => true,
            'message' => 'Tipo de lenguaje ' . $accion . ' exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo cambiar el estatus del tipo de lenguaje o no se encontró'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Error PDO en cambiar_estatus.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Exception $e) {
    error_log("Error general en cambiar_estatus.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>