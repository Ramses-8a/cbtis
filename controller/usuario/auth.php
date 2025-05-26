<?php
require_once(__DIR__ . '/../conexion.php');

session_start();

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

$sql = $connect->prepare("SELECT * FROM usuarios WHERE correo = :correo");
$sql->bindParam(':correo', $correo);
$sql->execute();

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($password, $usuario['password'])) {
    // Guardar info en sesión
    $_SESSION['usuario_id'] = $usuario['pk_usuario'];
    $_SESSION['usuario'] = $usuario['usuario'];
    
    echo json_encode([
        "status" => "success",
        "message" => "Inicio de sesion correcto",
        "usuario" => $usuario['usuario'],
        "Correo" => $usuario['correo']
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "credenciales incorrectos"
    ]);
}
