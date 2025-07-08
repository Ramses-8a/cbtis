<?php
require_once '../conexion.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se proporcionó un ID']);
    exit();
}

$id = $_POST['id'];

try {

    $stmt = $connect->prepare("SELECT * FROM cursos WHERE pk_curso = ?");
    $stmt->execute([$id]);
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        echo json_encode(['status' => 'error', 'message' => 'Curso no encontrado']);
        exit();
    }

    
    $stmt = $connect->prepare("DELETE FROM cursos WHERE pk_curso = ?");
    $stmt->execute([$id]);

    echo json_encode(['status' => 'success', 'message' => 'Curso eliminado correctamente']);
    exit();
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el curso: ' . $e->getMessage()]);
    exit();
}
