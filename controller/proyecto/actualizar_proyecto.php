<?php
header('Content-Type: application/json');
require_once('../conexion.php');

try {
    if (!isset($_POST['pk_proyecto'])) {
        throw new Exception('ID del proyecto no proporcionado');
    }

    $pk_proyecto = $_POST['pk_proyecto'];
    $nom_proyecto = $_POST['nom_proyecto'];
    $descripcion = $_POST['descripcion'];
    $detalles = $_POST['detalles'];

    // Actualizar datos básicos del proyecto
    $sql = "UPDATE proyectos SET nom_proyecto = ?, descripcion = ?, detalles = ? WHERE pk_proyecto = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$nom_proyecto, $descripcion, $detalles, $pk_proyecto]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode([
            'status' => 'info', 
            'message' => 'No se detectaron cambios en los datos del proyecto'
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success', 
        'message' => 'Proyecto actualizado correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}