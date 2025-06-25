<?php
require_once '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: ../../admin/lista_cursos.php?error=no_id");
    exit();
}

$id = $_GET['id'];

try {
    // Primero verifica si existe el curso
    $stmt = $connect->prepare("SELECT * FROM cursos WHERE pk_curso = ?");
    $stmt->execute([$id]);
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        header("Location: ../../admin/lista_cursos.php?error=not_found");
        exit();
    }

    // Luego, elimina el curso
    $stmt = $connect->prepare("DELETE FROM cursos WHERE pk_curso = ?");
    $stmt->execute([$id]);

    header("Location: ../../admin/lista_cursos.php?deleted=true");
    exit();
} catch (PDOException $e) {
    header("Location: ../../admin/lista_cursos.php?error=" . urlencode($e->getMessage()));
    exit();
}
