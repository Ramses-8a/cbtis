<?php
header('Content-Type: application/json');
include_once('../conexion.php');

try {
    // Verificar que se recibieron los datos necesarios
    if (!isset($_POST['id']) || !isset($_POST['nom_lenguaje']) || empty(trim($_POST['nom_lenguaje']))) {
        echo json_encode([
            'success' => false,
            'message' => 'ID y nombre del lenguaje son requeridos'
        ]);
        exit;
    }
    
    $id = $_POST['id'];
    $nom_lenguaje = trim($_POST['nom_lenguaje']);
    
    // Verificar si ya existe otro tipo de lenguaje con ese nombre (excluyendo el actual)
    $sql_check = "SELECT COUNT(*) FROM lenguajes WHERE nom_lenguaje = ? AND pk_lenguaje != ?";
    $stmt_check = $connect->prepare($sql_check);
    $stmt_check->execute([$nom_lenguaje, $id]);
    
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe otro tipo de lenguaje con ese nombre'
        ]);
        exit;
    }
    
    // Actualizar el tipo de lenguaje
    $sql = "UPDATE lenguajes SET nom_lenguaje = ? WHERE pk_lenguaje = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$nom_lenguaje, $id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Tipo de lenguaje actualizado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo actualizar el tipo de lenguaje o no se encontró'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Error PDO en actualizar_tipo.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Exception $e) {
    error_log("Error general en actualizar_tipo.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error_code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>