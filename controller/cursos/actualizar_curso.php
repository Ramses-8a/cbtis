<?php
include_once '../conexion.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pk_curso = $_POST['pk_curso'] ?? null;
    $nom_curso = $_POST['nom_curso'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $fk_tipo_curso = $_POST['fk_tipo_curso'] ?? null;
    $fk_lenguaje = $_POST['fk_lenguaje'] ?? null;
    $link = $_POST['link'] ?? null;
    $current_img = $_POST['current_img'] ?? null;

    if (empty($pk_curso) || empty($nom_curso) || empty($descripcion) || empty($fk_tipo_curso) || empty($fk_lenguaje) || empty($link)) {
        $response['message'] = 'Todos los campos son obligatorios, excepto la imagen.';
        echo json_encode($response);
        exit;
    }

    $img_name = null;
    if (!empty($current_img)) {
        $img_name = $current_img;
    }
    $upload_dir = '../../uploads/';

    if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $img_name = uniqid() . '.' . $img_extension;
        $img_path = $upload_dir . $img_name;

        if (!move_uploaded_file($img_tmp_name, $img_path)) {
            $response['message'] = 'Error al subir la nueva imagen.';
            echo json_encode($response);
            exit;
        }
        // Delete old image if it exists and is different from the new one
        if ($current_img && $current_img != $img_name && file_exists($upload_dir . $current_img)) {
            unlink($upload_dir . $current_img);
        }
    }

    try {
        $stmt = $connect->prepare("UPDATE cursos SET nom_curso = ?, descripcion = ?, fk_tipo_curso = ?, fk_lenguaje = ?, link = ?, img = ? WHERE pk_curso = ?");
        $stmt->bindParam(1, $nom_curso);
        $stmt->bindParam(2, $descripcion);
        $stmt->bindParam(3, $fk_tipo_curso);
        $stmt->bindParam(4, $fk_lenguaje);
        $stmt->bindParam(5, $link);
        $stmt->bindParam(6, $img_name);
        $stmt->bindParam(7, $pk_curso);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Curso actualizado correctamente.';
            $response['redirect_url'] = '../admin/lista_cursos.php';
        } else {
            $response['message'] = 'Error al actualizar el curso en la base de datos.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de solicitud no válido.';
}

echo json_encode($response);
?>