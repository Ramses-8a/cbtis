<?php
include_once '../conexion.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pk_curso = $_POST['pk_curso'] ?? null;
    $img_name = $_POST['img_name'] ?? null;

    if (empty($pk_curso) || empty($img_name)) {
        $response['message'] = 'ID del curso o nombre de la imagen no proporcionado.';
        echo json_encode($response);
        exit;
    }

    $upload_dir = '../../uploads/';
    $img_path = $upload_dir . $img_name;

    try {
        // Delete the file from the server
        if (file_exists($img_path)) {
            unlink($img_path);
        }

        // Update the database to set img to NULL
        $stmt = $connect->prepare("UPDATE cursos SET img = NULL WHERE pk_curso = ?");
        $stmt->bindParam(1, $pk_curso);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Imagen eliminada correctamente.';
        } else {
            $response['message'] = 'Error al actualizar la base de datos.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de solicitud no válido.';
}

echo json_encode($response);
?>