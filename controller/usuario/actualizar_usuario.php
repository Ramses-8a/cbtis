<?php
require_once(__DIR__ . '/../conexion.php');
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    $pk_usuario = $_POST['pk_usuario'] ?? null;
    $usuario = trim($_POST['usuario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $nueva_password = $_POST['nueva_password'] ?? '';
    $confirmar_password = $_POST['confirmar_password'] ?? '';

    if (!$pk_usuario || !$usuario || !$correo) {
        throw new Exception('Los campos usuario y correo son obligatorios.');
    }

    // 1. Verificar si el correo ya existe en otro usuario
    $stmt = $connect->prepare("SELECT pk_usuario FROM usuarios WHERE correo = :correo AND pk_usuario != :pk_usuario");
    $stmt->execute([
        ':correo' => $correo,
        ':pk_usuario' => $pk_usuario
    ]);

    if ($stmt->fetch()) {
        throw new Exception('El correo ya está registrado con otro usuario.');
    }

    // 2. Obtener usuario actual
    $stmt = $connect->prepare("SELECT * FROM usuarios WHERE pk_usuario = :pk_usuario");
    $stmt->execute([':pk_usuario' => $pk_usuario]);
    $usuario_actual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario_actual) {
        throw new Exception('Usuario no encontrado.');
    }

    // 3. Preparar cambios
    $update_fields = [];
    $update_params = [':pk_usuario' => $pk_usuario];

    if ($usuario_actual['usuario'] !== $usuario) {
        $update_fields[] = 'usuario = :usuario';
        $update_params[':usuario'] = $usuario;
    }

    if ($usuario_actual['correo'] !== $correo) {
        $update_fields[] = 'correo = :correo';
        $update_params[':correo'] = $correo;
    }

    // 4. Contraseña: solo si se envían ambas
    if (!empty($nueva_password) || !empty($confirmar_password)) {
        if ($nueva_password !== $confirmar_password) {
            throw new Exception('Las contraseñas no coinciden.');
        }

        if (strlen($nueva_password) < 6) {
            throw new Exception('La nueva contraseña debe tener al menos 6 caracteres.');
        }

        $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
        $update_fields[] = 'password = :password';
        $update_params[':password'] = $password_hash;
    }

    if (empty($update_fields)) {
        echo json_encode([
            'status' => 'warning',
            'message' => 'No se detectaron cambios.'
        ]);
        exit;
    }

    // 5. Ejecutar actualización
    $sql = "UPDATE usuarios SET " . implode(', ', $update_fields) . " WHERE pk_usuario = :pk_usuario";
    $stmt = $connect->prepare($sql);
    $stmt->execute($update_params);

    echo json_encode([
        'status' => 'success',
        'message' => 'Usuario actualizado correctamente.'
    ]);

} catch (Exception $e) {
    error_log("Error al actualizar usuario: " . $e->getMessage());

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
