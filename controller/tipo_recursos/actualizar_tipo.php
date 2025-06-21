<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom_tipo = $_POST['nom_tipo'];

    if (!empty($id) && !empty($nom_tipo)) {
        try {
            // Prepare an update statement usando $connect (no $conn)
            $stmt = $connect->prepare("UPDATE tipo_recursos SET nom_tipo = ? WHERE pk_tipo_recurso = ?");
            $stmt->bindParam(1, $nom_tipo, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Tipo de recurso actualizado correctamente.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al actualizar el tipo de recurso.';
            }
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'ID o nombre del tipo de recurso no proporcionado.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
}

// Devolver la respuesta JSON
echo json_encode($response);
?>