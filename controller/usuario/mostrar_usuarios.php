<?php
include_once(__DIR__ . '/../conexion.php');

$stmt = $connect->prepare("SELECT pk_usuario, usuario, correo, estatus FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

return $usuarios;