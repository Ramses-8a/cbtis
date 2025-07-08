<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function enviarCorreo($to, $token) {
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
        $mail->Port = $_ENV['MAIL_PORT'];

        // Remitente y destinatario
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($to);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Recuperar contraseña';
        $host = $_SERVER['HTTP_HOST'];
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $resetUrl = $protocol . $host . '/cbetis/admin/formulario_cambiar_password.php?token=' . $token;
        $mail->Body = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Recuperar contraseña</title>
    </head>
    <body style="background:#f5f5f5;padding:0;margin:0;">
        <div style="max-width:400px;margin:40px auto;background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:2.5rem 2rem 2rem 2rem;text-align:center;">
            <img src="cid:logo_cbtis" alt="Logo CBTIS" style="width:90px;margin-bottom:1.5rem;">
            <h2 style="color:#c62828;margin-bottom:1.2rem;">¿Olvidaste tu contraseña?</h2>
            <p style="margin-bottom:1.5rem;">Recibimos una solicitud para restablecer tu contraseña. Haz clic en el botón rojo para crear una nueva contraseña de acceso.</p>
            <a href="' . $resetUrl . '" style="display:inline-block;background:#c62828;color:#fff;text-decoration:none;padding:0.7rem 1.5rem;border-radius:5px;font-size:1rem;margin-bottom:1.2rem;">Restablecer contraseña</a>
            <p style="margin-top:2rem;font-size:0.95rem;color:#888;">Si no solicitaste este cambio, puedes ignorar este correo.</p>
            <p style="margin-top:1.5rem;"><a href="' . $protocol . $host . '/cbetis/index.php" style="color:#c62828;text-decoration:underline;">Volver al inicio</a></p>
        </div>
    </body>
    </html>';
        $mail->addEmbeddedImage(__DIR__ . '/../../img/logo_cbtis.png', 'logo_cbtis');

        $mail->send();
        return ['status' => 'success'];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => $mail->ErrorInfo];
    }
}

// Recibir petición POST
header('Content-Type: application/json');
require_once __DIR__ . '/../conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'] ?? '';
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // Validar si el correo existe en la tabla usuarios y obtener el token_ver
        $stmt = $connect->prepare('SELECT pk_usuario, token_ver FROM usuarios WHERE correo = ? LIMIT 1');
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && !empty($usuario['token_ver'])) {
            $token = $usuario['token_ver'];
            $host = $_SERVER['HTTP_HOST'];
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $resetUrl = $protocol . $host . '/cbetis/admin/formulario_cambiar_password.php?token=' . $token;
            $result = enviarCorreo($correo, $token);
            echo json_encode($result);
        } else {
            // Respuesta genérica
            echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el correo.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Correo inválido']);
    }
    exit;
}