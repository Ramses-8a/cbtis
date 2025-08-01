<?php
require_once(__DIR__ . '/../conexion.php');

$sql = $connect->prepare("SELECT * FROM proyectos");

try {
    $sql->execute();
    $proyectos = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $proyectos;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al traer los proyectos",
        "error" => $e->getMessage()
    ]);
    exit;
}
