<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {

        $sql_select = "SELECT estatus FROM proyectos WHERE pk_proyecto = :id";
        $stmt_select = $connect->prepare($sql_select);
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $proyecto = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($proyecto) {

            $nuevo_estatus = ($proyecto['estatus'] == 1) ? 0 : 1;
            $mensaje = ($nuevo_estatus == 1) ? 'activado' : 'desactivado';

            $sql_update = "UPDATE proyectos SET estatus = :nuevo_estatus WHERE pk_proyecto = :id";
            $stmt_update = $connect->prepare($sql_update);
            $stmt_update->bindParam(':nuevo_estatus', $nuevo_estatus, PDO::PARAM_INT);
            $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt_update->execute()) {
                // Redirigir a la página anterior
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status_changed=1&message=' . urlencode($mensaje));
                exit();
            } else {
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=update_failed');
                exit();
            }
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=not_found');
            exit();
        }

    } catch (PDOException $e) {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=no_id');
    exit();
}
?>