<?php
include '../controller/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "UPDATE proyectos SET estatus = 0 WHERE pk_proyecto = :id";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header('Location: lista_proyectos.php');
            exit();
        } else {
            echo "Error al dar de baja el proyecto.";
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    echo "ID no proporcionado.";
}
?>

