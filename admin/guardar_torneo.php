<?php
require_once(__DIR__ . '/../controller/conexion.php');
////NO ME DIGAN NADA PORQUE LOS DEMAS ARCHIVOS TAMBIEN ESTAN COMENTADOS
if (
    empty($_POST['nom_torneo']) ||
    empty($_POST['fk_tipo_torneo']) ||
    empty($_POST['descripcion']) ||
    empty($_POST['detalles']) ||
    !isset($_FILES['img_proyecto'])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan datos o la imagen principal"
    ]);
    exit;
}

// Validación de longitud
if (strlen($_POST['nom_torneo']) > 100 || strlen($_POST['descripcion']) > 255 || strlen($_POST['detalles']) > 1000) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Los campos exceden la longitud máxima permitida"
    ]);
    exit;
}

$nom_torneo = trim($_POST['nom_torneo']);
$fk_tipo_torneo = trim($_POST['fk_tipo_torneo']);
$descripcion = trim($_POST['descripcion']);
$detalles = trim($_POST['detalles']);
$estatus = 1;

// Validaciones de imagen
$img = $_FILES['img_proyecto'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

if (!in_array($img['type'], $allowed_types)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Tipo de imagen principal no permitido"
    ]);
    exit;
}

if ($img['size'] > $max_size) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "La imagen principal excede el tamaño máximo de 5MB"
    ]);
    exit;
}

$carpeta_destino = "../uploads/";
if (!is_dir($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true);
}

$img_nombre = uniqid() . "_" . basename($img['name']);
$ruta_img = $carpeta_destino . $img_nombre;

if (!move_uploaded_file($img['tmp_name'], $ruta_img)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al guardar la imagen principal"
    ]);
    exit;
}

// Iniciar transacción
$connect->beginTransaction();

try {
    // Insertar torneo principal
    $stmt = $connect->prepare("INSERT INTO torneos (nom_torneo, fk_tipo_torneo, estatus, img, descripcion, detalles)
                               VALUES (:nom_torneo, :fk_tipo_torneo, :estatus, :img, :descripcion, :detalles)");
    $stmt->bindParam(':nom_torneo', $nom_torneo);
    $stmt->bindParam(':fk_tipo_torneo', $fk_tipo_torneo);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    $stmt->bindParam(':img', $img_nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':detalles', $detalles);
    $stmt->execute();

    $id_torneo = $connect->lastInsertId();

    // Procesar imágenes adicionales
    if (isset($_FILES['img_adicionales']) && !empty($_FILES['img_adicionales']['name'][0])) {
        $total_adicionales = count($_FILES['img_adicionales']['name']);

        if ($total_adicionales > 10) {
            throw new Exception("No se pueden subir más de 10 imágenes adicionales");
        }

        for ($i = 0; $i < $total_adicionales; $i++) {
            if (!in_array($_FILES['img_adicionales']['type'][$i], $allowed_types)) {
                throw new Exception("Tipo de imagen adicional no permitido en la imagen " . ($i + 1));
            }

            if ($_FILES['img_adicionales']['size'][$i] > $max_size) {
                throw new Exception("Imagen adicional " . ($i + 1) . " excede el tamaño máximo");
            }

            $img_adicional_nombre = uniqid() . "_" . basename($_FILES['img_adicionales']['name'][$i]);
            $img_adicional_ruta = $carpeta_destino . $img_adicional_nombre;

            if (!move_uploaded_file($_FILES['img_adicionales']['tmp_name'][$i], $img_adicional_ruta)) {
                throw new Exception("Error al guardar la imagen adicional " . ($i + 1));
            }

            $stmt_img = $connect->prepare("INSERT INTO img_torneos (img, fk_torneo) VALUES (:img, :fk_torneo)");
            $stmt_img->bindParam(':img', $img_adicional_nombre);
            $stmt_img->bindParam(':fk_torneo', $id_torneo);
            $stmt_img->execute();
        }
    }

    $connect->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Torneo creado correctamente",
        "data" => [
            "nombre" => $nom_torneo,
            "imagen" => $img_nombre,
            "estatus" => $estatus,
            "total_imagenes_adicionales" => isset($total_adicionales) ? $total_adicionales : 0
        ]
    ]);
} catch (Exception $e) {
    $connect->rollBack();

    if (file_exists($ruta_img)) {
        unlink($ruta_img);
    }

    if (isset($total_adicionales)) {
        for ($i = 0; $i < $total_adicionales; $i++) {
            $img_temp_ruta = $carpeta_destino . uniqid() . "_" . basename($_FILES['img_adicionales']['name'][$i]);
            if (file_exists($img_temp_ruta)) {
                unlink($img_temp_ruta);
            }
        }
    }

    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
