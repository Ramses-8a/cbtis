<?php
require_once(__DIR__ . '/../conexion.php');

// Verificar si se recibió el ID
if (empty($_GET['pk_proyecto'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Falta el ID del proyecto"
    ]);
    exit;
}

$pk_proyecto = $_GET['pk_proyecto'];

// Preparar la consulta
$stmt = $connect->prepare("SELECT * FROM proyectos WHERE pk_proyecto = :pk_proyecto");
$stmt->bindParam(':pk_proyecto', $pk_proyecto, PDO::PARAM_INT);

try {
    $stmt->execute();
    $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($proyecto) {
        echo json_encode([
            "status" => "success",
            "data" => $proyecto
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No se encontró el proyecto"
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al buscar el proyecto",
        "error" => $e->getMessage()
    ]);
}