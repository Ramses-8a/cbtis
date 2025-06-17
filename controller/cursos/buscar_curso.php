<?php
require_once(__DIR__ . '/../conexion.php');

// Verificar si se recibió el ID
if (empty($_GET['pk_curso'])) {
    header('Location: ../../admin/lista_cursos.php?error=no_id');
    exit;
}

$pk_curso = $_GET['pk_curso'];

try {
    $stmt = $connect->prepare("SELECT * FROM cursos WHERE pk_curso = :pk_curso");
    $stmt->bindParam(':pk_curso', $pk_curso, PDO::PARAM_INT);
    $stmt->execute();
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        header('Location: ../../admin/lista_cursos.php?error=not_found');
        exit;
    }

} catch (PDOException $e) {
    header('Location: ../../admin/lista_cursos.php?error=db_error&message=' . urlencode($e->getMessage()));
    exit;
}