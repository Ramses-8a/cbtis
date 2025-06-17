<?php
require_once(__DIR__ . '/../conexion.php');

$usuario = $_POST['usuario'] ?? '';
$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';
$password_veri = $_POST['password_veri'] ?? '';

// Validar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "El formato del correo electrónico no es valido"
    ]);
    exit;
}

// Validar que las contraseñas coincidan
if ($password !== $password_veri) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Las contraseñas no coinciden"
    ]);
    exit;
}

// Verificar si el correo ya existe
$buscar_correo = $connect->prepare("SELECT * FROM usuarios WHERE correo = :correo");
$buscar_correo->bindParam(':correo', $correo, PDO::PARAM_STR);
$buscar_correo->execute();

if ($buscar_correo->rowCount() > 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "El correo electronico ya esta registrado"
    ]);
    exit;
}

// Hashear la contraseña
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Crear token de verificación
$token_ver = hash('sha256', $correo);

// Insertar usuario
$sql = $connect->prepare("INSERT INTO usuarios (usuario, correo, password, token_ver, estatus ) 
                          VALUES (:usuario, :correo, :password, :token_ver, 1 )");

$sql->bindParam(':usuario', $usuario, PDO::PARAM_STR);
$sql->bindParam(':correo', $correo, PDO::PARAM_STR);
$sql->bindParam(':password', $password_hash, PDO::PARAM_STR);
$sql->bindParam(':token_ver', $token_ver, PDO::PARAM_STR);

try {
    $sql->execute();
    echo json_encode([
        "status" => "success",
        "message" => "Usuario registrado correctamente",
        "data" => [
            "usuario" => $usuario,
            "correo" => $correo,
            "token" => $token_ver
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al registrar usuario",
        "error" => $e->getMessage()
    ]);
}
