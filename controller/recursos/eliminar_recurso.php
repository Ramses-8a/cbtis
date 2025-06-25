<?php
require_once(__DIR__ . '/../conexion.php');

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Iniciar transacción
        $connect->beginTransaction();

        // Luego eliminar el proyecto
        $sql_delete = "DELETE FROM recursos WHERE pk_recurso = :id";
        $stmt_delete = $connect->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();

        // Confirmar la transacción
        $connect->commit();

        echo json_encode(['status' => 'success', 'message' => 'Recurso eliminado correctamente']);
        exit();

    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $connect->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el recurso: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se proporcionó un ID']);
    exit();
}
?>