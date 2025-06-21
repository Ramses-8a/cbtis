<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $estatus_actual = $_POST['estatus'];

    if (!empty($id) && isset($estatus_actual)) {
        try {
            // Cambiar el estatus: si es 1 lo cambia a 0, si es 0 lo cambia a 1
            $nuevo_estatus = $estatus_actual == 1 ? 0 : 1;
            
            // Prepare an update statement usando $connect
            $stmt = $connect->prepare("UPDATE tipo_recursos SET estatus = ? WHERE pk_tipo_recurso = ?");
            $stmt->bindParam(1, $nuevo_estatus, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $accion = $nuevo_estatus == 1 ? 'dado de alta' : 'dado de baja';
                $response['status'] = 'success';
                $response['message'] = 'Tipo de recurso ' . $accion . ' correctamente.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al cambiar el estatus del tipo de recurso.';
            }
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'ID o estatus del tipo de recurso no proporcionado.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
}

// Devolver la respuesta JSON
echo json_encode($response);
?>