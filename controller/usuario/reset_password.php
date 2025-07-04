<?php
require_once '../conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validaciones básicas
if (empty($token) || empty($password) || empty($confirmPassword)) {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 8 caracteres']);
    exit;
}

try {
    // Verificar si el token es válido
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token_ver = ? AND activo = 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'El enlace no es válido o ha expirado']);
        exit;
    }

    // Actualizar la contraseña y limpiar el token
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE token_ver = ?");
    $stmt->bind_param("ss", $hashedPassword, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tu contraseña ha sido actualizada correctamente'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudo actualizar la contraseña. Por favor, intenta más tarde'
        ]);
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Ocurrió un error al procesar la solicitud'
    ]);
}

$conn->close();