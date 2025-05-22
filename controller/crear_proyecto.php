<?php
require_once('conexion.php');

// Verifica si todos los datos existen
if (
    empty($_POST['nom_proyecto']) ||
    empty($_POST['descripcion']) ||
    empty($_POST['detalles']) ||
    !isset($_FILES['img_proyecto'])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan datos o la imagen"
    ]);
    exit;
}

$nom_proyecto = $_POST['nom_proyecto'];
$descripcion = $_POST['descripcion'];
$detalles = $_POST['detalles'];
$estatus = 1; // Se establece en el backend

// Procesar imagen
$img = $_FILES['img_proyecto'];
$img_nombre = uniqid() . "_" . basename($img['name']);
$img_ruta = "../img/" . $img_nombre; // Ajustado

// Asegurarse de que la carpeta exista
if (!is_dir("../img")) {
    mkdir("../img", 0777, true);
}

if (!move_uploaded_file($img['tmp_name'], $img_ruta)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "No se pudo guardar la imagen"
    ]);
    exit;
}

// Guardar en la base de datos (solo el nombre del archivo o ruta relativa si prefieres)
$stmt = $connect->prepare("INSERT INTO proyectos (nom_proyecto, descripcion, detalles, estatus, img_proyecto)
                           VALUES (:nom_proyecto, :descripcion, :detalles, :estatus, :img_proyecto)");

$stmt->bindParam(':nom_proyecto', $nom_proyecto);
$stmt->bindParam(':descripcion', $descripcion);
$stmt->bindParam(':detalles', $detalles);
$stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
$stmt->bindParam(':img_proyecto', $img_nombre ); // o '../img/' . $img_nombre si quieres guardar la ruta completa

try {
    $stmt->execute();
    echo json_encode([
        "status" => "success",
        "message" => "Proyecto creado correctamente",
        "data" => [
            "nombre" => $nom_proyecto,
            "imagen" => $img_nombre, // puedes devolver también la URL completa si quieres
            "estatus" => $estatus,
            "url_imagen" => $img_nombre
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al guardar el proyecto",
        "error" => $e->getMessage()
    ]);
}
