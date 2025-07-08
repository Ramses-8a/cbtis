<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Obtener el estatus actual del tipo de curso
        $sql_select = "SELECT estatus FROM tipo_cursos WHERE pk_tipo_curso = :id";
        $stmt_select = $connect->prepare($sql_select);
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $tipo = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($tipo) {
            // Determinar el nuevo estatus (0 si es 1, 1 si es 0)
            $nuevo_estatus = ($tipo['estatus'] == 1) ? 0 : 1;
            $mensaje = ($nuevo_estatus == 1) ? 'activado' : 'desactivado';

            // Actualizar el estatus en la base de datos
            $sql_update = "UPDATE tipo_cursos SET estatus = :nuevo_estatus WHERE pk_tipo_curso = :id";
            $stmt_update = $connect->prepare($sql_update);
            $stmt_update->bindParam(':nuevo_estatus', $nuevo_estatus, PDO::PARAM_INT);
            $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt_update->execute()) {
                // Redireccionar con mensaje de éxito
                header("Location: ../../admin/formulario_tipo_curso.php?status_changed=1&message=$mensaje");
                exit;
            } else {
                // Redireccionar con mensaje de error
                header("Location: ../../admin/formulario_tipo_curso.php?error=update_failed");
                exit;
            }
        } else {
            // Redireccionar con mensaje de error
            header("Location: ../../admin/formulario_tipo_curso.php?error=not_found");
            exit;
        }
    } catch (PDOException $e) {
        // Redireccionar con mensaje de error
        header("Location: ../../admin/formulario_tipo_curso.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Redireccionar con mensaje de error
    header("Location: ../../admin/formulario_tipo_curso.php?error=no_id");
    exit;
}