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

    // --- NUEVO MANEJO DE IMÁGENES ADICIONALES ---
    $imagenes_a_eliminar = isset($_POST['imagenes_a_eliminar']) ? $_POST['imagenes_a_eliminar'] : [];
    
    // Debug: verificar qué llega en $_FILES
    error_log('DEBUG $_FILES: ' . print_r($_FILES, true));
    error_log('DEBUG $_POST imagenes_a_eliminar: ' . print_r($imagenes_a_eliminar, true));
    
    $nuevas_imagenes = isset($_FILES['img_adicionales']) && 
                      isset($_FILES['img_adicionales']['name']) && 
                      is_array($_FILES['img_adicionales']['name']) && 
                      !empty($_FILES['img_adicionales']['name'][0]);

    // Verificar si hay cambios en los datos o imágenes adicionales
    $stmt_check = $connect->prepare("SELECT nom_proyecto, descripcion, detalles, url FROM proyectos WHERE pk_proyecto = ?");
    $stmt_check->execute([$pk_proyecto]);
    $proyecto_actual = $stmt_check->fetch(PDO::FETCH_ASSOC);

    // Verificar imágenes adicionales actuales
    $stmt_imgs = $connect->prepare("SELECT pk_img_proyectos FROM img_proyectos WHERE fk_proyecto = ?");
    $stmt_imgs->execute([$pk_proyecto]);
    $imgs_actuales = $stmt_imgs->fetchAll(PDO::FETCH_COLUMN);

    $hay_cambios = false;
    if ($proyecto_actual['nom_proyecto'] !== $nom_proyecto ||
        $proyecto_actual['descripcion'] !== $descripcion ||
        $proyecto_actual['detalles'] !== $detalles ||
        $proyecto_actual['url'] !== $url ||
        $img_proyecto !== null) {
        $hay_cambios = true;
    }
    // Si hay imágenes a eliminar
    if (!empty($imagenes_a_eliminar)) {
        $hay_cambios = true;
    }
    // Si hay nuevas imágenes
    if ($nuevas_imagenes) {
        $hay_cambios = true;
    }
    if (!$hay_cambios) {
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

    // Eliminar imágenes adicionales seleccionadas
    if (!empty($imagenes_a_eliminar)) {
        $in = str_repeat('?,', count($imagenes_a_eliminar) - 1) . '?';
        $stmt_del = $connect->prepare("DELETE FROM img_proyectos WHERE pk_img_proyectos IN ($in) AND fk_proyecto = ?");
        $params = array_merge($imagenes_a_eliminar, [$pk_proyecto]);
        $stmt_del->execute($params);
    }

    // Agregar nuevas imágenes adicionales
    if ($nuevas_imagenes) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $total_adicionales = count($_FILES['img_adicionales']['name']);
        if ($total_adicionales > 10) {
            throw new Exception("No se pueden subir más de 10 imágenes adicionales");
        }
        for ($i = 0; $i < $total_adicionales; $i++) {
            if (!in_array($_FILES['img_adicionales']['type'][$i], $allowed_types)) {
                throw new Exception("Tipo de archivo no permitido en imagen adicional " . ($i + 1));
            }
            if ($_FILES['img_adicionales']['size'][$i] > $max_size) {
                throw new Exception("La imagen adicional " . ($i + 1) . " es demasiado grande");
            }
            $img_adicional_nombre = uniqid() . "_" . basename($_FILES['img_adicionales']['name'][$i]);
            $img_adicional_ruta = "../../uploads/" . $img_adicional_nombre;
            if (!move_uploaded_file($_FILES['img_adicionales']['tmp_name'][$i], $img_adicional_ruta)) {
                throw new Exception("No se pudo guardar la imagen adicional " . ($i + 1));
            }
            $stmt_img = $connect->prepare("INSERT INTO img_proyectos (img, fk_proyecto) VALUES (?, ?)");
            $stmt_img->execute([$img_adicional_nombre, $pk_proyecto]);
        }
    }

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