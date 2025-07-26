<?php
include_once(__DIR__ . '/../conexion.php');

$pk_usuario = $_GET['pk_usuario'];

$stmt = $connect->prepare("SELECT pk_usuario, usuario, correo, estatus FROM usuarios WHERE pk_usuario = ?");
$stmt->execute([$pk_usuario]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
return $usuario;
