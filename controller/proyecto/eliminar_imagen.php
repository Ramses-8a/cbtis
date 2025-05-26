<?php
require_once(__DIR__ . '/../conexion.php');
header('Content-Type: application/json');

if (!isset($_POST['pk_img_proyectos'])) {
    http_response_code(400);
    die(json_encode([
        'status' => 'error',
        'message' => 'ID de imagen no proporcionado'
    ]));
}

$pk_img_proyectos = (int)$_POST['pk_img_proyectos'];

try {
    // Obtener información de la imagen antes de eliminar
    $stmt = $connect->prepare("SELECT img, fk_proyecto FROM img_proyectos WHERE pk_img_proyectos = ?");
    $stmt->execute([$pk_img_proyectos]);
    $imagen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$imagen) {
        throw new Exception('Imagen no encontrada');
    }

    // Verificar que el archivo exista antes de intentar eliminarlo
    $ruta_imagen = __DIR__ . '/../../img/' . $imagen['img'];
    
    // Eliminar el registro de la base de datos
    $stmt = $connect->prepare("DELETE FROM img_proyectos WHERE pk_img_proyectos = ?");
    $stmt->execute([$pk_img_proyectos]);

    // Eliminar el archivo físico si existe
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen);
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Imagen eliminada correctamente'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}