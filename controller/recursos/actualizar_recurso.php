<?php
require_once(__DIR__ . '/../conexion.php');

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    error_log('POST data recibida: ' . print_r($_POST, true));
    $pk_recurso = $_POST['pk_recurso'] ?? null;
    $nom_recurso = $_POST['nom_recurso'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $fk_tipo_recurso = $_POST['pk_tipo_recurso'] ?? null;
    $url = $_POST['url'] ?? null;
    $current_img = $_POST['current_img'] ?? null;

    if (!$pk_recurso || !$nom_recurso || !$descripcion || !$fk_tipo_recurso || !$url) {
        throw new Exception('Todos los campos obligatorios deben ser rellenados.');
    }

    // Obtener datos actuales del recurso
    $stmt = $connect->prepare("SELECT * FROM recursos WHERE pk_recurso = :pk_recurso");
    $stmt->bindParam(':pk_recurso', $pk_recurso, PDO::PARAM_INT);
    $stmt->execute();
    $recurso_actual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recurso_actual) {
        throw new Exception('Recurso no encontrado.');
    }

    $has_changes = false;
    $update_fields = [];
    $update_params = [];

    if ($recurso_actual['nom_recurso'] !== $nom_recurso) {
        $update_fields[] = 'nom_recurso = :nom_recurso';
        $update_params[':nom_recurso'] = $nom_recurso;
        $has_changes = true;
    }
    if ($recurso_actual['descripcion'] !== $descripcion) {
        $update_fields[] = 'descripcion = :descripcion';
        $update_params[':descripcion'] = $descripcion;
        $has_changes = true;
    }
    if ($recurso_actual['fk_tipo_recurso'] != $fk_tipo_recurso) {
        $update_fields[] = 'fk_tipo_recurso = :fk_tipo_recurso';
        $update_params[':fk_tipo_recurso'] = $fk_tipo_recurso;
        $has_changes = true;
    }
    if ($recurso_actual['url'] !== $url) {
        $update_fields[] = 'url = :url';
        $update_params[':url'] = $url;
        $has_changes = true;
    }

    $new_img_name = $current_img;
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['img']['tmp_name'];
        $file_name = $_FILES['img']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($file_ext, $allowed_ext)) {
            throw new Exception('Formato de imagen no permitido.');
        }

        $new_img_name = uniqid() . '_' . basename($file_name);
        $upload_dir = __DIR__ . '/../../uploads/';
        $upload_path = $upload_dir . $new_img_name;

        if (!move_uploaded_file($file_tmp_name, $upload_path)) {
            throw new Exception('Error al subir la nueva imagen.');
        }

        $update_fields[] = 'img = :img';
        $update_params[':img'] = $new_img_name;
        $has_changes = true;

        // Eliminar imagen antigua si existe y es diferente
        if ($recurso_actual['img'] && $recurso_actual['img'] !== $new_img_name && file_exists($upload_dir . $recurso_actual['img'])) {
            unlink($upload_dir . $recurso_actual['img']);
        }
    }

    if (!$has_changes) {
        echo json_encode([
            'status' => 'warning',
            'message' => 'No se detectaron cambios en el recurso'
        ]);
        exit;
    }

    $update_params[':pk_recurso'] = $pk_recurso;
    $sql = "UPDATE recursos SET " . implode(', ', $update_fields) . " WHERE pk_recurso = :pk_recurso";
    $stmt = $connect->prepare($sql);

    if (!$stmt->execute($update_params)) {
        throw new Exception('Error al actualizar el recurso en la base de datos.');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Recurso actualizado correctamente',
        'redirect_url' => '../admin/lista_recursos.php'
    ]);

} catch (Exception $e) {
    error_log('Error en actualizar_recurso.php: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

?>