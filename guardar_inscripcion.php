<?php
require_once 'controller/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pk_torneo = $_POST['pk_torneo'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $grado = trim($_POST['grado'] ?? '');
    $grupo = trim($_POST['grupo'] ?? '');

    if (!$pk_torneo || empty($nombre) || empty($grado) || empty($grupo)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
        exit;
    }

    $sql = "INSERT INTO alumno_torneos (fk_torneo, nombre, grado, grupo) VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);

    try {
        $stmt->execute([$pk_torneo, $nombre, $grado, $grupo]);
        echo json_encode(['success' => true, 'message' => 'Inscripción guardada correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
