<?php
require_once('../conexion.php');

// Verificar que se reciban los datos necesarios
if (!isset($_POST['pk_proyecto']) || !isset($_POST['nom_proyecto']) || !isset($_POST['descripcion']) || 
    !isset($_POST['detalles']) || !isset($_POST['estatus'])) {
    echo json_encode(['error' => 'Faltan datos requeridos']);
    exit;
}

// Obtener los datos del formulario
$pk_proyecto = $_POST['pk_proyecto'];
$nom_proyecto = $_POST['nom_proyecto'];
$descripcion = $_POST['descripcion'];
$detalles = $_POST['detalles'];
$estatus = $_POST['estatus'];

// Preparar la consulta base
$sql = "UPDATE proyectos SET 
        nom_proyecto = :nom_proyecto, 
        descripcion = :descripcion, 
        detalles = :detalles, 
        estatus = :estatus";

$params = [
    ':pk_proyecto' => $pk_proyecto,
    ':nom_proyecto' => $nom_proyecto,
    ':descripcion' => $descripcion,
    ':detalles' => $detalles,
    ':estatus' => $estatus
];

// Manejar la imagen si se envió una nueva
if (isset($_FILES['img_proyecto']) && $_FILES['img_proyecto']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['img_proyecto'];
    $fileName = uniqid() . '_' . $file['name'];
    $uploadPath = '../img/' . $fileName;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $sql .= ", img_proyecto = :img_proyecto";
        $params[':img_proyecto'] = $fileName;
    } else {
        echo json_encode(['error' => 'Error al subir la imagen']);
        exit;
    }
}

// Completar la consulta con la condición WHERE
$sql .= " WHERE pk_proyecto = :pk_proyecto";

try {
    $stmt = $connect->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => 'Proyecto actualizado correctamente']);
    } else {
        echo json_encode(['error' => 'No se encontró el proyecto o no hay cambios que actualizar']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al actualizar el proyecto: ' . $e->getMessage()]);
}