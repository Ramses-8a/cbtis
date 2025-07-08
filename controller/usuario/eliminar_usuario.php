<?php
include_once(__DIR__ . '/../conexion.php');

if (!isset($_POST['pk_usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado']);
    exit;
}

$pk_usuario = $_POST['pk_usuario'];

try {
    // Verificar si el usuario existe
    $stmt = $connect->prepare("SELECT pk_usuario FROM usuarios WHERE pk_usuario = ?");
    $stmt->execute([$pk_usuario]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
        exit;
    }

    // Eliminar el usuario
    $stmt = $connect->prepare("DELETE FROM usuarios WHERE pk_usuario = ?");
    $stmt->execute([$pk_usuario]);

    echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el usuario']);
}