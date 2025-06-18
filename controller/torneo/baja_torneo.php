<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Obtener el estatus actual del proyecto
        $sql_select = "SELECT estatus FROM torneos WHERE pk_torneo = :id";
        $stmt_select = $connect->prepare($sql_select);
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $torneo = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($torneo) {
            // Determinar el nuevo estatus (0 si es 1, 1 si es 0)
            $nuevo_estatus = ($torneo['estatus'] == 1) ? 0 : 1;
            $mensaje = ($nuevo_estatus == 1) ? 'activado' : 'desactivado';

            // Actualizar el estatus en la base de datos
            $sql_update = "UPDATE torneos SET estatus = :nuevo_estatus WHERE pk_torneo = :id";
            $stmt_update = $connect->prepare($sql_update);
            $stmt_update->bindParam(':nuevo_estatus', $nuevo_estatus, PDO::PARAM_INT);
            $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt_update->execute()) {
                // Redirigir con mensaje de éxito
                header('Location: ../../admin/lista_torneos.php?status_changed=1&message=' . urlencode($mensaje));
                exit();
            } else {
                header('Location: ../../admin/lista_torneos.php?error=update_failed');
                exit();
            }
        } else {
            header('Location: ../../admin/lista_torneos.php?error=not_found');
            exit();
        }

    } catch (PDOException $e) {
        header('Location: ../../admin/lista_torneos.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ../../admin/lista_torneos.php?error=no_id');
    exit();
}
?>