<?php
require_once(__DIR__ . '/../conexion.php');

header('Content-Type: application/json');

$response = array('status' => 'error', 'message' => 'Invalid request.');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_tipo = $_POST['id'];

    try {
        $stmt = $connect->prepare("DELETE FROM tipo_recursos WHERE pk_tipo_recurso = ?");
        if ($stmt->execute([$id_tipo])) {
            $response = array('status' => 'success', 'message' => 'Tipo de recurso eliminado correctamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error al eliminar el tipo de recurso.');
        }
    } catch (PDOException $e) {
        // Verificar si es un error de clave foránea
        if ($e->getCode() == '23000' || strpos($e->getMessage(), 'foreign key constraint') !== false || strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
            $response = array('status' => 'error', 'message' => 'El tipo de recurso no se puede eliminar porque se utiliza en uno o más recursos.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error de base de datos: ' . $e->getMessage());
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'ID de tipo de recurso no proporcionado.');
}

echo json_encode($response);