<?php
require_once(__DIR__ . '/../conexion.php');

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
       
        $connect->beginTransaction();

        $sql_delete = "DELETE FROM recursos WHERE pk_recurso = :id";
        $stmt_delete = $connect->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();

        $connect->commit();

        header('Location: ../../admin/lista_recursos.php?deleted=1');
        echo json_encode(['status' => 'success', 'message' => 'Recurso eliminado correctamente']);
        exit();

    } catch (PDOException $e) {
    
        $connect->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el recurso: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se proporcionó un ID']);
    exit();
}
?>