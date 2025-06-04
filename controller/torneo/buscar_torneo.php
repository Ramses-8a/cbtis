<?php
require_once(__DIR__ . '/../conexion.php');

// Verificar si se recibió el ID
if (empty($_GET['pk_torneo'])) {
    http_response_code(400);
    die(json_encode([
        "status" => "error",
        "message" => "Falta el ID del torneo"
    ]));
}

$pk_torneo = $_GET['pk_torneo'];

try {
    // Obtener el torneo
    $stmt = $connect->prepare("SELECT * FROM torneos WHERE pk_torneo = :pk_torneo AND estatus = 1");
    $stmt->bindParam(':pk_torneo', $pk_torneo, PDO::PARAM_INT);
    $stmt->execute();
    $torneo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($torneo) {
        // // Obtener las imágenes adicionales
        // $stmt_img = $connect->prepare("SELECT * FROM img_torneos WHERE fk_torneo = :fk_torneo");
        // $stmt_img->bindParam(':fk_torneo', $pk_torneo, PDO::PARAM_INT);
        // $stmt_img->execute();
        // $imagenes_adicionales = $stmt_img->fetchAll(PDO::FETCH_ASSOC);
        
        // $torneo['imagenes_adicionales'] = $imagenes_adicionales;
        
        return $torneo;
    } else {
        http_response_code(404);
        die(json_encode([
            "status" => "error",
            "message" => "No se encontró el torneo"
        ]));
    }
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode([
        "status" => "error",
        "message" => "Error al buscar el torneo",
        "error" => $e->getMessage()
    ]));
}