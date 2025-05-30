<?php
require_once(__DIR__ . '/../conexion.php');

$sql = $connect->prepare("SELECT * FROM torneos WHERE estatus = 1");

try {
    $sql->execute();

    if ($sql->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No se encontraron proyectos"
        ]);
    }

    $torneos = $sql->fetchAll(PDO::FETCH_ASSOC);

    // json_encode([
    //     "status" => "success",
    //     "message" => "Se encontraron proyectos",
    //     "proyectos" => $sql->fetchAll(PDO::FETCH_ASSOC)
    // ]);

    return $torneos;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al traer los torneos",
        "error" => $e->getMessage()
    ]);
}
