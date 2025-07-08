<?php
require_once(__DIR__ . '/../conexion.php');
if (!isset($_GET['pk_recurso']) || empty($_GET['pk_recurso'])) {
    exit("No se especificó ningún recurso.");
}

$pk_recurso = intval($_GET['pk_recurso']);

try {
    $stmt = $connect->prepare("
        SELECT 
            r.fk_tipo_recurso,
            r.nom_recurso,
            r.url,
            r.img,
            r.descripcion,
            tr.nom_tipo
        FROM recursos r
        LEFT JOIN tipo_recursos tr ON r.fk_tipo_recurso = tr.pk_tipo_recurso
        WHERE r.pk_recurso = ?
    ");
    $stmt->execute([$pk_recurso]);
    $recurso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recurso) {
        exit("Recurso no encontrado.");
    }

} catch (PDOException $e) {
    exit("Error en la consulta: " . $e->getMessage());
}

?>