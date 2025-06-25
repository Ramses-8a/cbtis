<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');


try {
    // Validar campos requeridos
    $required_fields = ['pk_proyecto', 'nom_proyecto', 'descripcion', 'detalles', 'url'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception('El campo ' . $field . ' es obligatorio');
        }
    }

    $pk_proyecto = $_POST['pk_proyecto'];
    $nom_proyecto = $_POST['nom_proyecto'];
    $descripcion = $_POST['descripcion'];
    $detalles = $_POST['detalles'];
    $url = $_POST['url'];

    // Validate URL format
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        http_response_code(200);
        echo json_encode([
            'status' => 'warning',
            'message' => 'El formato de la URL no es válido'
        ]);
        die();
    }


    // Verificar si se ha proporcionado una nueva imagen principal
    $img_proyecto = null;
    if (isset($_FILES['img_proyecto']) && $_FILES['img_proyecto']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['img_proyecto'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($img['type'], $allowed_types)) {
            throw new Exception('Tipo de archivo no permitido para la imagen principal.');
        }

        if ($img['size'] > $max_size) {
            throw new Exception('La imagen principal es demasiado grande.');
        }

        $img_nombre = uniqid() . "_" . basename($img['name']);
        $img_ruta = "../../uploads/" . $img_nombre;

        if (!move_uploaded_file($img['tmp_name'], $img_ruta)) {
            throw new Exception('No se pudo guardar la imagen principal.');
        }

        $img_proyecto = $img_nombre;
    }

    // Iniciar transacción
    $connect->beginTransaction();

    // Verificar si hay cambios en los datos
    $stmt_check = $connect->prepare("SELECT nom_proyecto, descripcion, detalles, url FROM proyectos WHERE pk_proyecto = ?");
    $stmt_check->execute([$pk_proyecto]);
    $proyecto_actual = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($proyecto_actual['nom_proyecto'] === $nom_proyecto &&
        $proyecto_actual['descripcion'] === $descripcion &&
        $proyecto_actual['detalles'] === $detalles &&
        $proyecto_actual['url'] === $url &&
        $img_proyecto === null) {
        
        $connect->commit();

        echo json_encode([
            'status' => 'warning',
            'message' => 'No se detectaron cambios en el proyecto'
        ]);
        die();
    }

    // Actualizar datos del proyecto
    $sql = "UPDATE proyectos SET nom_proyecto = ?, descripcion = ?, detalles = ?, url = ?, img_proyecto = COALESCE(?, img_proyecto) WHERE pk_proyecto = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$nom_proyecto, $descripcion, $detalles, $url, $img_proyecto, $pk_proyecto]);

    // Confirmar transacción
    $connect->commit();
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Proyecto actualizado correctamente',
        'redirect_url' => '../admin/lista_proyectos.php'
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($connect->inTransaction()) {
        $connect->rollBack();
    }
    
    // Eliminar la imagen principal nueva si existe
    if (isset($img_ruta) && file_exists($img_ruta)) {
        unlink($img_ruta);
    }

    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}