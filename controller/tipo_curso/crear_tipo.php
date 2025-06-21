<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_tipo = $_POST['nom_tipo'];

    if (!empty($nom_tipo)) {
        try {
            // Prepare an insert statement usando $connect
            $stmt = $connect->prepare("INSERT INTO tipo_cursos (nom_tipo) VALUES (?)");
            $stmt->bindParam(1, $nom_tipo, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['status'] = 'success';
                $response['message'] = 'Tipo de curso creado correctamente.';
            } else {
                $response['success'] = false;
                $response['status'] = 'error';
                $response['message'] = 'Error al crear el tipo de curso.';
            }
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['status'] = 'error';
            $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
        }
    } else {
        $response['success'] = false;
        $response['status'] = 'error';
        $response['message'] = 'Nombre del tipo de curso no proporcionado.';
    }
} else {
    $response['success'] = false;
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
}

// Devolver la respuesta JSON
echo json_encode($response);
?>