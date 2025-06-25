<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
       
        $connect->beginTransaction();

        $sql_delete = "DELETE FROM recursos WHERE pk_recurso = :id";
        $stmt_delete = $connect->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();

        $connect->commit();

        header('Location: ../../admin/lista_recursos.php?deleted=1');
        exit();

    } catch (PDOException $e) {
    
        $connect->rollBack();
        header('Location: ../../admin/lista_recursos.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ../../admin/lista_recursos.php?error=no_id');
    exit();
}
?>