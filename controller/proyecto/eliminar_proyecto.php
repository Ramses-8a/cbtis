<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
     
        $connect->beginTransaction();

       
        $sql_delete_images = "DELETE FROM img_proyectos WHERE fk_proyecto = :id";
        $stmt_delete_images = $connect->prepare($sql_delete_images);
        $stmt_delete_images->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete_images->execute();

        $sql_delete = "DELETE FROM proyectos WHERE pk_proyecto = :id";
        $stmt_delete = $connect->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();

        $connect->commit();

        header('Location: ../../admin/lista_proyectos.php?deleted=1');
        exit();

    } catch (PDOException $e) {
        
        $connect->rollBack();
        header('Location: ../../admin/lista_proyectos.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ../../admin/lista_proyectos.php?error=no_id');
    exit();
}
?>