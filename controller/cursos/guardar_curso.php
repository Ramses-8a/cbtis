<?php
require_once('../conexion.php');

// Limpiar cualquier salida previa
ob_clean();
header('Content-Type: application/json');

try {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validar campos
    if (
        empty($_POST['nom_curso']) ||
        empty($_POST['fk_tipo_curso']) ||
        empty($_POST['fk_lenguaje']) ||
        empty($_POST['link']) ||
        empty($_POST['descripcion'])
    ) {
        throw new Exception('Todos los campos son obligatorios.');
    }

    if (!isset($_FILES['img_curso'])) {
        throw new Exception('No se ha subido ninguna imagen.');
    }

    if ($_FILES['img_curso']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir la imagen. Código: ' . $_FILES['img_curso']['error']);
    }

    $nom_curso = $_POST['nom_curso'];
    $fk_tipo_curso = $_POST['fk_tipo_curso'];
    $fk_lenguaje = $_POST['fk_lenguaje'];
    $link = $_POST['link'];
    $descripcion = $_POST['descripcion'];

    // Procesar imagen
    $img = $_FILES['img_curso'];
    $ext = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed)) {
        throw new Exception('Tipo de archivo no permitido. Solo jpg, jpeg, png y gif.');
    }

    $nombreImagen = uniqid() . '.' . $ext;
    $directorioDestino = '../../uploads/';

    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }

    $rutaCompleta = $directorioDestino . $nombreImagen;

    if (!move_uploaded_file($img['tmp_name'], $rutaCompleta)) {
        throw new Exception('No se pudo mover la imagen.');
    }

    $rutaBD = 'uploads/' . $nombreImagen;

    // Insertar en la base de datos
    $sql = "INSERT INTO cursos (nom_curso, fk_tipo_curso, fk_lenguaje, link, descripcion, img, estatus)
            VALUES (:nom_curso, :fk_tipo_curso, :fk_lenguaje, :link, :descripcion, :img, 1)";
    $stmt = $connect->prepare($sql);
    $stmt->execute([
        ':nom_curso' => $nom_curso,
        ':fk_tipo_curso' => $fk_tipo_curso,
        ':fk_lenguaje' => $fk_lenguaje,
        ':link' => $link,
        ':descripcion' => $descripcion,
        ':img' => $rutaBD
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Curso guardado correctamente.'
    ]);
    exit();
} catch (PDOException $pdoEx) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la base de datos: ' . $pdoEx->getMessage()
    ]);
    exit();
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit();
}
