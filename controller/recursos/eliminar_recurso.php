<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

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

        // Redirigir con mensaje de éxito
        header('Location: ../../admin/lista_recursos.php?deleted=1');
        exit();

    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $connect->rollBack();
        header('Location: ../../admin/lista_recursos.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ../../admin/lista_recursos.php?error=no_id');
    exit();
}
?>