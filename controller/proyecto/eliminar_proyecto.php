<?php
require_once(__DIR__ . '/../conexion.php');
header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Iniciar transacción
        $connect->beginTransaction();

        // Primero eliminar las imágenes asociadas
        $sql_delete_images = "DELETE FROM img_proyectos WHERE fk_proyecto = :id";
        $stmt_delete_images = $connect->prepare($sql_delete_images);
        $stmt_delete_images->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete_images->execute();

        // Luego eliminar el proyecto
        $sql_delete = "DELETE FROM proyectos WHERE pk_proyecto = :id";
        $stmt_delete = $connect->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();

        // Confirmar la transacción
        $connect->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'El proyecto ha sido eliminado exitosamente'
        ]);
        exit();

    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $connect->rollBack();
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar el proyecto: ' . $e->getMessage()
        ]);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'No se proporcionó el ID del proyecto'
    ]);
    exit();
}
?>