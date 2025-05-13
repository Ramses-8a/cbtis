<?php
require_once('conexion.php');

$usuario = $_POST['usuario'];
$correo = $_POST['correo'];
$password = $_POST['password'];
$password_veri = $_POST['password_veri'];

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "El formato del correo electrónico no es válido"
    ]);
    exit();
}

if($password !== $password_veri) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Las contraseñas no coinciden"
    ]);
    exit();
}

$token_ver = hash('sha256', $correo);

$sql = "INSERT INTO usuarios (usuario,correo,password,token_ver,estatus,tipo) values(:usuario,:correo,:password,:token_ver,1,1)";

$sql = $connect->prepare($sql);

$sql->bindParam(':usuario',$usuario,PDO::PARAM_STR, 55);
$sql->bindParam(':correo',$correo,PDO::PARAM_STR, 55);
$sql->bindParam(':password',$password,PDO::PARAM_STR,55);
$sql->bindParam(':token_ver',$token_ver,PDO::PARAM_STR,255);

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
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al registrar usuario",
        "error" => $e->getMessage()
    ]);
}