<?php
session_start();
include_once(__DIR__ . '/../conexion.php');
header('Content-Type: application/json');

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$pk_usuario = $_POST['pk_usuario'] ?? null;

// Validar ID de usuario
if (!$pk_usuario || !is_numeric($pk_usuario)) {
    echo json_encode(['status' => 'error', 'message' => 'ID de usuario inválido']);
    exit;
}

// Verificar si el usuario intenta desactivarse a sí mismo
if ($_SESSION['usuario_id'] == $pk_usuario) {
    echo json_encode(['status' => 'error', 'message' => 'No puedes desactivar tu propio usuario']);
    exit;
}

try {
    // Verificar si el usuario existe
    $stmt = $connect->prepare("SELECT pk_usuario, estatus FROM usuarios WHERE pk_usuario = ?");
    $stmt->execute([$pk_usuario]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
        exit;
    }

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $new_status = $usuario['estatus'] == 1 ? 0 : 1;
    $message = $new_status == 1 ? 'Usuario activado correctamente' : 'Usuario desactivado correctamente';

    // Actualizar el estatus en la base de datos
    $stmt = $connect->prepare("UPDATE usuarios SET estatus = ? WHERE pk_usuario = ?");
    $stmt->execute([$new_status, $pk_usuario]);

    echo json_encode(['status' => 'success', 'message' => $message]);
} catch (PDOException $e) {
    error_log('Error en eliminar_usuario.php: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud']);
}