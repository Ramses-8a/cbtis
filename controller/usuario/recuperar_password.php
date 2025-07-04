<?php
require_once '../conexion.php';
require_once '../mail/Mailer.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$correo) {
    echo json_encode(['status' => 'error', 'message' => 'Correo electrónico inválido']);
    exit;
}

try {
    // Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ? AND activo = 1");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró una cuenta con este correo electrónico']);
        exit;
    }

    // Obtener el token_ver existente del usuario
    $stmt = $conn->prepare("SELECT token_ver FROM usuarios WHERE correo = ? AND activo = 1");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $token = $usuario['token_ver'];

    if ($token) {
    try {
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/cb/cbtis/admin/reset_password.php?token=" . $token;
        $mailer = new Mailer();
        $mailer->sendPasswordReset($correo, $resetLink);

        echo json_encode([
            'status' => 'success',
            'message' => 'Se han enviado las instrucciones a tu correo electrónico'
        ]);
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudo enviar el correo electrónico. Por favor, intenta más tarde'
        ]);
    }
}

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Ocurrió un error al procesar la solicitud'
    ]);
}

$conn->close();