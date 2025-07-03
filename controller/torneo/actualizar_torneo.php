<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');

$response = ['status' => 'error', 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de solicitud no permitido.');
    }

    $pk_torneo = $_POST['pk_torneo'] ?? null;
    $nom_torneo = $_POST['nom_torneo'] ?? null;
    $fk_tipo_torneo = $_POST['fk_tipo_torneo'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $detalles = $_POST['detalles'] ?? null;
    $finicio = $_POST['finicio'] ?? null;
    $ffinal = $_POST['ffinal'] ?? null;

    if (!$pk_torneo || !$nom_torneo || !$fk_tipo_torneo || !$descripcion || !$detalles || !$finicio || !$ffinal) {
        throw new Exception('Todos los campos son obligatorios.');
    }

    $img_name = null;
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_name = uniqid() . '_' . basename($_FILES['img']['name']);
        $upload_dir = '../../img/';
        $target_file = $upload_dir . $img_name;

        if (!move_uploaded_file($img_tmp_name, $target_file)) {
            throw new Exception('Error al subir la imagen.');
        }
    } else if (isset($_POST['current_img']) && !empty($_POST['current_img'])) {
        $img_name = $_POST['current_img'];
    }

    // Start transaction
    $connect->beginTransaction();

    // Check for changes before updating (optional, but good practice)
    $stmt_check = $connect->prepare("SELECT nom_torneo, fk_tipo_torneo, estatus, descripcion, detalles, img, finicio, ffinal FROM torneos WHERE pk_torneo = ?");
    $stmt_check->execute([$pk_torneo]);
    $torneo_actual = $stmt_check->fetch(PDO::FETCH_ASSOC);

    error_log('Torneo Actual: ' . print_r($torneo_actual, true));
    error_log('Datos Recibidos (POST): nom_torneo=' . $nom_torneo . ', fk_tipo_torneo=' . $fk_tipo_torneo . ', descripcion=' . $descripcion . ', detalles=' . $detalles . ', img_name=' . $img_name . ', finicio=' . $finicio . ', ffinal=' . $ffinal);

    $has_changes = false;
    if ($torneo_actual['nom_torneo'] !== $nom_torneo) {
        error_log('Cambio detectado en nom_torneo: ' . $torneo_actual['nom_torneo'] . ' vs ' . $nom_torneo);
        $has_changes = true;
    }
    if ($torneo_actual['fk_tipo_torneo'] != $fk_tipo_torneo) {
        error_log('Cambio detectado en fk_tipo_torneo: ' . $torneo_actual['fk_tipo_torneo'] . ' vs ' . $fk_tipo_torneo);
        $has_changes = true;
    }
    if ($torneo_actual['descripcion'] !== $descripcion) {
        error_log('Cambio detectado en descripcion: ' . $torneo_actual['descripcion'] . ' vs ' . $descripcion);
        $has_changes = true;
    }
    if ($torneo_actual['detalles'] !== $detalles) {
        error_log('Cambio detectado en detalles: ' . $torneo_actual['detalles'] . ' vs ' . $detalles);
        $has_changes = true;
    }
    if ($torneo_actual['finicio'] !== $finicio) {
        error_log('Cambio detectado en fecha de inicio: ' . $torneo_actual['finicio'] . ' vs ' . $finicio);
        $has_changes = true;
    }
    if ($torneo_actual['ffinal'] !== $ffinal) {
        error_log('Cambio detectado en fecha final: ' . $torneo_actual['ffinal'] . ' vs ' . $ffinal);
        $has_changes = true;
    }

    // Check if image has changed
    if ($img_name && $torneo_actual['img'] !== $img_name) {
        error_log('Cambio detectado en imagen: ' . $torneo_actual['img'] . ' vs ' . $img_name);
        $has_changes = true;
    } elseif (!$img_name && $torneo_actual['img'] !== null) {
        // If no new image and current image exists, it means user wants to remove it (if that's the logic)
        // For now, we assume if no new image, keep old one unless explicitly removed.
        // If you want to allow removing image, you'd need a checkbox or similar.
        error_log('Imagen eliminada (no se envió nueva imagen y existía una anterior).');
        // Dependiendo de la lógica de negocio, esto podría ser un cambio o no.
        // Por ahora, no lo marcamos como cambio a menos que se implemente una opción de "eliminar imagen".
    }

    error_log('Has Changes final: ' . ($has_changes ? 'true' : 'false'));

    if (!$has_changes) {
        $connect->commit();
        $response['status'] = 'warning';
        $response['message'] = 'No se detectaron cambios en el torneo.';
        echo json_encode($response);
        die();
    }

    // Update tournament data
    $sql = "UPDATE torneos SET nom_torneo = ?, fk_tipo_torneo = ?, descripcion = ?, detalles = ?, finicio = ?, ffinal = ?";
    $params = [$nom_torneo, $fk_tipo_torneo, $descripcion, $detalles, $finicio, $ffinal];

    if ($img_name) {
        $sql .= ", img = ?";
        $params[] = $img_name;
    }

    $sql .= " WHERE pk_torneo = ?";
    $params[] = $pk_torneo;

    $stmt = $connect->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Error al preparar la consulta: " . $connect->errorInfo()[2]);
    }

    if ($stmt->execute($params)) {
        $response['status'] = 'success';
        $response['message'] = 'Torneo actualizado correctamente.';
        $response['redirect_url'] = '../admin/lista_torneos.php';
    } else {
        throw new Exception('Error al actualizar el torneo: ' . $stmt->errorInfo()[2]);
    }

    // Commit transaction
    $connect->commit();

} catch (Exception $e) {
    // Rollback transaction on error
    if ($connect->inTransaction()) {
        $connect->rollBack();
    }
    
    // Delete the new image if an error occurred after upload
    if (isset($target_file) && file_exists($target_file)) {
        unlink($target_file);
    }

    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>