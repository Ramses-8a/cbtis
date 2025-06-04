<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de solicitud no permitido.');
    }

    $pk_torneo = $_POST['pk_torneo'] ?? null;
    $nom_torneo = $_POST['nom_torneo'] ?? null;
    $fk_tipo_torneo = $_POST['fk_tipo_torneo'] ?? null;
    $estatus = $_POST['estatus'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $detalles = $_POST['detalles'] ?? null;

    if (!$pk_torneo || !$nom_torneo || !$fk_tipo_torneo || !$estatus || !$descripcion || !$detalles) {
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
    $stmt_check = $connect->prepare("SELECT nom_torneo, fk_tipo_torneo, estatus, descripcion, detalles, img FROM torneos WHERE pk_torneo = ?");
    $stmt_check->execute([$pk_torneo]);
    $torneo_actual = $stmt_check->fetch(PDO::FETCH_ASSOC);

    $has_changes = false;
    if ($torneo_actual['nom_torneo'] !== $nom_torneo ||
        $torneo_actual['fk_tipo_torneo'] !== $fk_tipo_torneo ||
        $torneo_actual['estatus'] !== $estatus ||
        $torneo_actual['descripcion'] !== $descripcion ||
        $torneo_actual['detalles'] !== $detalles) {
        $has_changes = true;
    }

    // Check if image has changed
    if ($img_name && $torneo_actual['img'] !== $img_name) {
        $has_changes = true;
    } elseif (!$img_name && $torneo_actual['img'] !== null) {
        // If no new image and current image exists, it means user wants to remove it (if that's the logic)
        // For now, we assume if no new image, keep old one unless explicitly removed.
        // If you want to allow removing image, you'd need a checkbox or similar.
    }

    if (!$has_changes) {
        $connect->commit();
        $response['success'] = true;
        $response['message'] = 'No se detectaron cambios en el torneo.';
        echo json_encode($response);
        exit;
    }

    // Update tournament data
    $sql = "UPDATE torneos SET nom_torneo = ?, fk_tipo_torneo = ?, estatus = ?, descripcion = ?, detalles = ?";
    $params = [$nom_torneo, $fk_tipo_torneo, $estatus, $descripcion, $detalles];

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
        $response['success'] = true;
        $response['message'] = 'Torneo actualizado correctamente.';
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