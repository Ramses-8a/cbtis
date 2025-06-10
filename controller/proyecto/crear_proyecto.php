<?php
require_once(__DIR__ . '/../conexion.php');

if (
    empty($_POST['nom_recurso']) ||
    empty($_POST['descripcion']) ||
    empty($_POST['pk_tipo_recurso']) ||
    empty($_POST['url']) || // URL ahora es obligatoria
    !isset($_FILES['img'])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan datos obligatorios"
    ]);
    exit;
}

$nombre = trim($_POST['nom_recurso']);
$descripcion = trim($_POST['descripcion']);
$tipo = intval($_POST['pk_tipo_recurso']);
$url = trim($_POST['url']);
$estatus = 1;

// Validar longitud de los campos
if (strlen($nombre) > 100 || strlen($descripcion) > 255 || strlen($url) > 255) {
    echo json_encode(["status" => "error", "message" => "Los campos exceden la longitud permitida."]);
    exit;
}

// Validar URL obligatoria y válida
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(["status" => "error", "message" => "URL inválida."]);
    exit;
}

// Validar imagen
$img = $_FILES['img'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

if (!in_array($img['type'], $allowed_types)) {
    echo json_encode(["status" => "error", "message" => "Formato de imagen no permitido."]);
    exit;
}

if ($img['size'] > $max_size) {
    echo json_encode(["status" => "error", "message" => "Imagen demasiado grande. Máximo: 5MB"]);
    exit;
}

// Guardar imagen
$img_nombre = uniqid() . "_" . basename($img['name']);
$img_ruta = "../../img/" . $img_nombre;

if (!is_dir("../../img")) {
    mkdir("../../img", 0777, true);
}

if (!move_uploaded_file($img['tmp_name'], $img_ruta)) {
    echo json_encode(["status" => "error", "message" => "No se pudo guardar la imagen"]);
    exit;
}

// Guardar en la base de datos
try {
    $stmt = $connect->prepare("INSERT INTO recursos (nom_recurso, descripcion, pk_tipo_recurso, url, img, estatus)
        VALUES (:nombre, :descripcion, :tipo, :url, :imagen, 1)");
    
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':imagen', $img_nombre);
    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "message" => "Recurso guardado exitosamente",
        "redirect_url" => "../../admin/lista_recursos.php"
    ]);
} catch (Exception $e) {
    if (file_exists($img_ruta)) unlink($img_ruta);
    echo json_encode([
        "status" => "error",
        "message" => "Error al guardar en la base de datos: " . $e->getMessage()
    ]);
}
?>
