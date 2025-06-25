<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../conexion.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
    echo json_encode($response);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['nom_tipo'])) {
    $response['status'] = 'error';
    $response['message'] = 'Faltan campos requeridos.';
    echo json_encode($response);
    exit;
}

$id = trim($_POST['id']);
$nom_tipo = trim($_POST['nom_tipo']);

if (empty($id) || empty($nom_tipo)) {
    $response['status'] = 'error';
    $response['message'] = 'Los campos no pueden estar vacíos.';
    echo json_encode($response);
    exit;
}
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

// Devolver la respuesta JSON
echo json_encode($response);
?>