<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Obtener el estatus actual del tipo de lenguaje
        $sql_select = "SELECT estatus FROM lenguajes WHERE pk_lenguaje = :id";
        $stmt_select = $connect->prepare($sql_select);
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $tipo = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($tipo) {
            // Determinar el nuevo estatus (0 si es 1, 1 si es 0)
            $nuevo_estatus = ($tipo['estatus'] == 1) ? 0 : 1;
            $mensaje = ($nuevo_estatus == 1) ? 'activado' : 'desactivado';

            // Actualizar el estatus en la base de datos
            $sql_update = "UPDATE lenguajes SET estatus = :nuevo_estatus WHERE pk_lenguaje = :id";
            $stmt_update = $connect->prepare($sql_update);
            $stmt_update->bindParam(':nuevo_estatus', $nuevo_estatus, PDO::PARAM_INT);
            $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt_update->execute()) {
                // Redireccionar con mensaje de éxito
                header("Location: ../../admin/formulario_tipo_lenguaje.php?status_changed=1&message=$mensaje");
                exit;
            } else {
                // Redireccionar con mensaje de error
                header("Location: ../../admin/formulario_tipo_lenguaje.php?error=update_failed");
                exit;
            }
        } else {
            // Redireccionar con mensaje de error
            header("Location: ../../admin/formulario_tipo_lenguaje.php?error=not_found");
            exit;
        }
    } catch (PDOException $e) {
        // Redireccionar con mensaje de error
        header("Location: ../../admin/formulario_tipo_lenguaje.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Redireccionar con mensaje de error
    header("Location: ../../admin/formulario_tipo_lenguaje.php?error=no_id");
    exit;
}