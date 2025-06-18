<?php
require_once(__DIR__ . '/../conexion.php');

$sql = $connect->prepare("SELECT * FROM proyectos ORDER BY pk_proyecto DESC LIMIT 3");

try {
    $sql->execute();
    $proyectos = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Siempre retorna un arreglo (puede estar vacío)
    return $proyectos;

} catch (PDOException $e) {
    // Si hay error real de conexión o consulta, sí mostramos mensaje de error
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al traer los proyectos",
        "error" => $e->getMessage()
    ]);
    exit;
}
