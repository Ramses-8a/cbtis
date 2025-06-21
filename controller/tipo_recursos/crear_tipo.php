<?php
require_once('../conexion.php');

$nom_tipo = trim($_POST['nom_tipo']);

// Validar si el campo está vacío
if (empty($nom_tipo)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No se pueden enviar campos vacíos.'
    ]);
    exit;
}

try {
    $stmt = $connect->prepare('INSERT INTO tipo_recursos (nom_tipo) VALUES (:nom_tipo)');
    $stmt->bindParam(':nom_tipo', $nom_tipo);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Tipo de recurso creado correctamente'
    ]);
    exit;
} catch (\Throwable $th) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Hubo un error al insertar'
    ]);
    exit;
}
