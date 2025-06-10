<?php
require_once(__DIR__ . '/../conexion.php');

// Validar que los datos necesarios estén presentes
if (
    empty($_POST['nom_recurso']) ||
    empty($_POST['descripcion']) ||
    empty($_POST['pk_tipo_recurso']) ||
    empty($_POST['url']) ||
    !isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan datos o la imagen principal no se subió correctamente."
    ]);
    exit;
}

// Asignar variables y limpiar datos
$nom_recurso = trim($_POST['nom_recurso']);
$descripcion = trim($_POST['descripcion']);
$pk_tipo_recurso = (int)$_POST['pk_tipo_recurso'];
$url = trim($_POST['url']);
$estatus = 1; // Estatus por defecto activo

// Validación de longitud de campos
if (strlen($nom_recurso) > 255 || strlen($descripcion) > 1000 || strlen($url) > 255) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Los campos exceden la longitud máxima permitida."
    ]);
    exit;
}

// Validación de URL usando filter_var
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "La URL proporcionada no es válida."
    ]);
    exit;
}

// Validación de imagen principal
$img_file = $_FILES['img'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

// Verificar tipo de archivo principal
if (!in_array($img_file['type'], $allowed_types)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Tipo de archivo no permitido para la imagen principal. Solo se permiten: JPG, PNG, GIF, SVG y WEBP."
    ]);
    exit;
}

// Verificar tamaño de archivo principal
if ($img_file['size'] > $max_size) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "La imagen principal es demasiado grande. Tamaño máximo: 5MB."
    ]);
    exit;
}

$img_nombre = uniqid() . "_" . basename($img_file['name']);
$img_ruta = "../../uploads/" . $img_nombre; // Cambiado a la carpeta 'uploads'

// Asegurarse de que la carpeta exista
if (!is_dir("../../uploads")) {
    mkdir("../../uploads", 0777, true);
}

if (!move_uploaded_file($img_file['tmp_name'], $img_ruta)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "No se pudo guardar la imagen principal."
    ]);
    exit;
}

// Iniciar transacción
$connect->beginTransaction();

try {
    // Insertar recurso
    $stmt = $connect->prepare("INSERT INTO recursos (nom_recurso, descripcion, fk_tipo_recurso, url, estatus, img)
                             VALUES (:nom_recurso, :descripcion, :fk_tipo_recurso, :url, :estatus, :img)");

    $stmt->bindParam(':nom_recurso', $nom_recurso);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':fk_tipo_recurso', $pk_tipo_recurso, PDO::PARAM_INT);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    $stmt->bindParam(':img', $img_nombre);
    $stmt->execute();
    
    // Confirmar transacción
    $connect->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Recurso creado correctamente.",
        "redirect_url" => "../admin/lista_recursos.php" // Asumiendo que habrá una lista de recursos
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $connect->rollBack();
    
    // Eliminar la imagen principal si existe
    if (file_exists($img_ruta)) {
        unlink($img_ruta);
    }
    
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al crear el recurso: " . $e->getMessage()
    ]);
}