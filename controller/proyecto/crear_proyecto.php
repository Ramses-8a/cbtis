<?php
require_once(__DIR__ . '/../conexion.php');

if (
    empty($_POST['nom_proyecto']) ||
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

// Validación de longitud de campos
if (strlen($_POST['nom_proyecto']) > 100 || strlen($_POST['descripcion']) > 255 || strlen($_POST['detalles']) > 1000) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Los campos exceden la longitud máxima permitida"
    ]);
    exit;
}

$nom_proyecto = trim($_POST['nom_proyecto']);
$descripcion = trim($_POST['descripcion']);
$detalles = trim($_POST['detalles']);
$estatus = 1;

// Validación de imagen principal
$img = $_FILES['img_proyecto'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

// Verificar tipo de archivo principal
if (!in_array($img['type'], $allowed_types)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Tipo de archivo no permitido para la imagen principal. Solo se permiten: JPG, PNG, GIF, SVG y WEBP"
    ]);
    exit;
}

// Verificar tamaño de archivo principal
if ($img['size'] > $max_size) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "La imagen principal es demasiado grande. Tamaño máximo: 5MB"
    ]);
    exit;
}

$img_nombre = uniqid() . "_" . basename($img['name']);
$img_ruta = "../../img/" . $img_nombre;

// Asegurarse de que la carpeta exista
if (!is_dir("../../img")) {
    mkdir("../../img", 0777, true);
}

if (!move_uploaded_file($img['tmp_name'], $img_ruta)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "No se pudo guardar la imagen principal"
    ]);
    exit;
}

// Iniciar transacción
$connect->beginTransaction();

try {
    // Insertar proyecto principal
    $stmt = $connect->prepare("INSERT INTO proyectos (nom_proyecto, descripcion, detalles, estatus, img_proyecto)
                             VALUES (:nom_proyecto, :descripcion, :detalles, :estatus, :img_proyecto)");

    $stmt->bindParam(':nom_proyecto', $nom_proyecto);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':detalles', $detalles);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    $stmt->bindParam(':img_proyecto', $img_nombre);
    $stmt->execute();
    
    $proyecto_id = $connect->lastInsertId();

    // Procesar imágenes adicionales
    if (isset($_FILES['img_adicionales']) && !empty($_FILES['img_adicionales']['name'][0])) {
        $total_adicionales = count($_FILES['img_adicionales']['name']);
        
        if ($total_adicionales > 10) {
            throw new Exception("No se pueden subir más de 10 imágenes adicionales");
        }

        for ($i = 0; $i < $total_adicionales; $i++) {
            // Validar cada imagen adicional
            if (!in_array($_FILES['img_adicionales']['type'][$i], $allowed_types)) {
                throw new Exception("Tipo de archivo no permitido en imagen adicional " . ($i + 1));
            }

            if ($_FILES['img_adicionales']['size'][$i] > $max_size) {
                throw new Exception("Imagen adicional " . ($i + 1) . " excede el tamaño máximo de 5MB");
            }

            $img_adicional_nombre = uniqid() . "_" . basename($_FILES['img_adicionales']['name'][$i]);
            $img_adicional_ruta = "../../img/" . $img_adicional_nombre;

            if (!move_uploaded_file($_FILES['img_adicionales']['tmp_name'][$i], $img_adicional_ruta)) {
                throw new Exception("Error al guardar imagen adicional " . ($i + 1));
            }

            // Insertar en la tabla img_proyectos
            $stmt_img = $connect->prepare("INSERT INTO img_proyectos (img, fk_proyecto) VALUES (:img, :fk_proyecto)");
            $stmt_img->bindParam(':img', $img_adicional_nombre);
            $stmt_img->bindParam(':fk_proyecto', $proyecto_id);
            $stmt_img->execute();
        }
    }

    // Confirmar transacción
    $connect->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Proyecto creado correctamente con todas sus imágenes",
        "data" => [
            "nombre" => $nom_proyecto,
            "imagen" => $img_nombre,
            "estatus" => $estatus,
            "url_imagen" => $img_nombre,
            "total_imagenes_adicionales" => isset($total_adicionales) ? $total_adicionales : 0
        ]
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $connect->rollBack();
    
    // Eliminar la imagen principal si existe
    if (file_exists($img_ruta)) {
        unlink($img_ruta);
    }
    
    // Eliminar cualquier imagen adicional que se haya guardado
    if (isset($total_adicionales)) {
        for ($i = 0; $i < $total_adicionales; $i++) {
            $img_adicional_ruta = "../../img/" . uniqid() . "_" . basename($_FILES['img_adicionales']['name'][$i]);
            if (file_exists($img_adicional_ruta)) {
                unlink($img_adicional_ruta);
            }
        }
    }

    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}