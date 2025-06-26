<?php
require_once(__DIR__ . '/../conexion.php');

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Iniciar transacción
        $connect->beginTransaction();

        // Primero eliminar las inscripciones de alumnos asociadas al torneo
        $sql_delete_alumno_torneos = "DELETE FROM alumno_torneos WHERE fk_torneo = :id";
        $stmt_delete_alumno_torneos = $connect->prepare($sql_delete_alumno_torneos);
        $stmt_delete_alumno_torneos->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete_alumno_torneos->execute();

        // Luego eliminar el torneo
        $sql_delete = "DELETE FROM torneos WHERE pk_torneo = :id";
        $stmt_delete = $connect->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();

        // Confirmar la transacción
        $connect->commit();

        // Devolver respuesta de éxito
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Torneo eliminado correctamente']);
        exit();

    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $connect->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el torneo: ' . $e->getMessage()]);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No se proporcionó el ID del torneo']);
    exit();
}