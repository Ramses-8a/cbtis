<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Iniciar transacción
        $connect->beginTransaction();

        $sql_delete = "DELETE FROM torneos WHERE pk_torneo = :id";
        $stmt_delete = $connect->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);

        // Confirmar la transacción
        $connect->commit();

        // Redirigir con mensaje de éxito
        header('Location: ../../admin/lista_torneos.php?deleted=1');
        exit();

    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $connect->rollBack();
        header('Location: ../../admin/lista_torneos.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ../../admin/lista_torneos.php?error=no_id');
    exit();
}
?>