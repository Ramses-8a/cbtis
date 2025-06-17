<?php
require_once(__DIR__ . '/../conexion.php');

// Verificar si se recibió el ID
if (empty($_GET['pk_recurso'])) {
    header('Location: ../../admin/lista_recursos.php?error=no_id');
    exit;
}

$pk_recurso = $_GET['pk_recurso'];

try {
    $stmt = $connect->prepare("SELECT * FROM recursos WHERE pk_recurso = :pk_recurso");
    $stmt->bindParam(':pk_recurso', $pk_recurso, PDO::PARAM_INT);
    $stmt->execute();
    $recurso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recurso) {
        header('Location: ../../admin/lista_recursos.php?error=not_found');
        exit;
    }

} catch (PDOException $e) {
    header('Location: ../../admin/lista_recursos.php?error=db_error&message=' . urlencode($e->getMessage()));
    exit;
}