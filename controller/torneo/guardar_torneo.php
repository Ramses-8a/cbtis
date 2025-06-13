<?php
require_once(__DIR__ . '/../conexion.php');

if (
    empty($_POST['nom_torneo']) ||
    empty($_POST['fk_tipo_torneo']) ||
    empty($_POST['descripcion']) ||
    empty($_POST['detalles']) ||
    !isset($_FILES['img_proyecto'])
) {
    http_response_code(200);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan datos o la imagen principal"
    ]);
    die();
}

// Validación de longitud
if (strlen($_POST['nom_torneo']) > 100 || strlen($_POST['descripcion']) > 255 || strlen($_POST['detalles']) > 1000) {
    http_response_code(200);
    echo json_encode([
        "status" => "error",
        "message" => "Los campos exceden la longitud máxima permitida"
    ]);
    die();
}

$nom_torneo = trim($_POST['nom_torneo']);
$fk_tipo_torneo = trim($_POST['fk_tipo_torneo']);
$descripcion = trim($_POST['descripcion']);
$detalles = trim($_POST['detalles']);
$estatus = 1;

// Validaciones de imagen principal
$img = $_FILES['img_proyecto'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

if (!in_array($img['type'], $allowed_types)) {
    http_response_code(200);
    echo json_encode([
        "status" => "error",
        "message" => "Tipo de imagen principal no permitido"
    ]);
    die();
}

if ($img['size'] > $max_size) {
    http_response_code(200);
    echo json_encode([
        "status" => "error",
        "message" => "La imagen principal excede el tamaño máximo de 5MB"
    ]);
    die();
}

$carpeta_destino = "../../uploads/";
if (!is_dir($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true);
}

$img_nombre = uniqid() . "_" . basename($img['name']);
$ruta_img = $carpeta_destino . $img_nombre;

if (!move_uploaded_file($img['tmp_name'], $ruta_img)) {
    http_response_code(200);
    echo json_encode([
        "status" => "error",
        "message" => "Error al guardar la imagen principal"
    ]);
    die();
}

// Iniciar transacción
$connect->beginTransaction();

try {
    // Insertar torneo
    $stmt = $connect->prepare("INSERT INTO torneos (nom_torneo, fk_tipo_torneo, estatus, img, descripcion, detalles)
                               VALUES (:nom_torneo, :fk_tipo_torneo, :estatus, :img, :descripcion, :detalles)");
    $stmt->bindParam(':nom_torneo', $nom_torneo);
    $stmt->bindParam(':fk_tipo_torneo', $fk_tipo_torneo);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    $stmt->bindParam(':img', $img_nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':detalles', $detalles);
    $stmt->execute();

    $connect->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Torneo creado correctamente",
        "redirect_url" => "../admin/lista_torneos.php",
        "data" => [
            "nombre" => $nom_torneo,
            "imagen" => $img_nombre,
            "estatus" => $estatus
        ]
    ]);
    die();
} catch (Exception $e) {
    $connect->rollBack();

    if (file_exists($ruta_img)) {
        unlink($ruta_img);
    }

    http_response_code(200);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    die();
}
?>
