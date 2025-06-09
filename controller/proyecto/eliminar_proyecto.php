<?php
require_once(__DIR__ . '/../conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Verificar que el proyecto existe antes de eliminar
        $sql_check = "SELECT * FROM proyectos WHERE pk_proyecto = :id";
        $stmt_check = $connect->prepare($sql_check);
        $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check->execute();
        $proyecto = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($proyecto) {
            // Eliminar el proyecto
            $sql_delete = "DELETE FROM proyectos WHERE pk_proyecto = :id";
            $stmt_delete = $connect->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_delete->execute();

            header("Location: ../views/proyectos.php?deleted=1");
            exit;
        } else {
            echo "Proyecto no encontrado.";
        }

    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    echo "ID no proporcionado.";
}
?>
