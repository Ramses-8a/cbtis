<?php
require_once(__DIR__ . '/../conexion.php');

// Verificar si se recibió el parámetro
if (!isset($_GET['pk_recurso']) || !is_numeric($_GET['pk_recurso'])) {
    die("Recurso no válido.");
}

$pk_recurso = (int)$_GET['pk_recurso'];

try {
    $stmt = $connect->prepare("
        SELECT r.*, tr.nom_tipo 
        FROM recursos r
        LEFT JOIN tipo_recursos tr ON r.fk_tipo_recurso = tr.pk_tipo_recurso
        WHERE r.pk_recurso = :id
    ");
    $stmt->bindParam(':id', $pk_recurso, PDO::PARAM_INT);
    $stmt->execute();
    $recurso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recurso) {
        die("Recurso no encontrado.");
    }

} catch (PDOException $e) {
    die("Error al obtener el recurso: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($recurso['nom_recurso']) ?></title>
    <link rel="stylesheet" href="../../css/proyecto.css">
</head>
<body>

    <div class="con_volver">
        <a href="../../mostrar_recursos.php" class="volver">
            <img src="../../img/regresar.png" alt="Volver">
        </a>
        <h3><?= htmlspecialchars($recurso['nom_recurso']) ?></h3>
    </div>

    <main class="detalle-recurso" style="padding: 2rem;">
        <?php if (!empty($recurso['img'])): ?>
            <img src="../../uploads/<?= htmlspecialchars($recurso['img']) ?>" alt="Imagen del recurso" style="max-width:300px;">
        <?php endif; ?>

        <h2><?= htmlspecialchars($recurso['nom_recurso']) ?></h2>
        <p><strong>Tipo:</strong> <?= htmlspecialchars($recurso['nom_tipo']) ?></p>
        <p><strong>Descripción:</strong><br> <?= nl2br(htmlspecialchars($recurso['descripcion'])) ?></p>

        <?php if (!empty($recurso['url'])): ?>
            <p><a href="<?= htmlspecialchars($recurso['url']) ?>" target="_blank">Ir al recurso</a></p>
        <?php endif; ?>
    </main>

</body>
</html>
