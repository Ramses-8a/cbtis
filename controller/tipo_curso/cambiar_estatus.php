<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nuevo_estatus = $_POST['nuevo_estatus'];

    if (!empty($id) && isset($nuevo_estatus)) {
        try {
            // Preparar la consulta para actualizar el estatus
            $stmt = $connect->prepare("UPDATE tipo_cursos SET estatus = ? WHERE pk_tipo_curso = ?");
            $stmt->bindParam(1, $nuevo_estatus, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $estatus_texto = $nuevo_estatus == 1 ? 'activado' : 'desactivado';
                $response['success'] = true;
                $response['status'] = 'success';
                $response['message'] = 'El tipo de curso ha sido ' . $estatus_texto . ' correctamente.';
            } else {
                $response['success'] = false;
                $response['status'] = 'error';
                $response['message'] = 'Error al cambiar el estatus del tipo de curso.';
            }
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['status'] = 'error';
            $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
        }
    } else {
        $response['success'] = false;
        $response['status'] = 'error';
        $response['message'] = 'Datos incompletos para cambiar el estatus.';
    }
} else {
    $response['success'] = false;
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
}

// Devolver la respuesta JSON
echo json_encode($response);
?>