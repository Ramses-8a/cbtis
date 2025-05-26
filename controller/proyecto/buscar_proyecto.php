<?php
require_once(__DIR__ . '/../conexion.php');

// Verificar si se recibió el ID
if (empty($_GET['pk_proyecto'])) {
    http_response_code(400);
    die(json_encode([
        "status" => "error",
        "message" => "Falta el ID del proyecto"
    ]));
}

$pk_proyecto = $_GET['pk_proyecto'];

try {
    // Obtener el proyecto
    $stmt = $connect->prepare("SELECT * FROM proyectos WHERE pk_proyecto = :pk_proyecto AND estatus = 1");
    $stmt->bindParam(':pk_proyecto', $pk_proyecto, PDO::PARAM_INT);
    $stmt->execute();
    $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($proyecto) {
        // Obtener las imágenes adicionales
        $stmt_img = $connect->prepare("SELECT * FROM img_proyectos WHERE fk_proyecto = :fk_proyecto");
        $stmt_img->bindParam(':fk_proyecto', $pk_proyecto, PDO::PARAM_INT);
        $stmt_img->execute();
        $imagenes_adicionales = $stmt_img->fetchAll(PDO::FETCH_ASSOC);
        
        $proyecto['imagenes_adicionales'] = $imagenes_adicionales;
        
        return $proyecto;
    } else {
        http_response_code(404);
        die(json_encode([
            "status" => "error",
            "message" => "No se encontró el proyecto"
        ]));
    }
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode([
        "status" => "error",
        "message" => "Error al buscar el proyecto",
        "error" => $e->getMessage()
    ]));
}