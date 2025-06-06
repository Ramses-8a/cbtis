<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Obtener el estatus actual del proyecto
        $sql_select = "SELECT estatus FROM proyectos WHERE pk_proyecto = :id";
        $stmt_select = $connect->prepare($sql_select);
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $proyecto = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($proyecto) {
            // Determinar el nuevo estatus (0 si es 1, 1 si es 0)
            $nuevo_estatus = ($proyecto['estatus'] == 1) ? 0 : 1;

            // Actualizar el estatus en la base de datos
            $sql_update = "UPDATE proyectos SET estatus = :nuevo_estatus WHERE pk_proyecto = :id";
            $stmt_update = $connect->prepare($sql_update);
            $stmt_update->bindParam(':nuevo_estatus', $nuevo_estatus, PDO::PARAM_INT);
            $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt_update->execute()) {
                // Redirigir a la página anterior usando JavaScript
                echo '<script>history.back();</script>';
                exit();
            } else {
                // Manejar error de actualización
                echo "Error al actualizar el estatus del proyecto.";
            }
        } else {
            // Manejar caso donde no se encuentra el proyecto
            echo "Proyecto no encontrado.";
        }

    } catch (PDOException $e) {
        // Manejar errores de base de datos
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    // Manejar caso donde no se proporciona el ID
    echo "ID no proporcionado.";
}
?>