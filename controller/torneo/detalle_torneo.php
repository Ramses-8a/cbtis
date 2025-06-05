<?php
require_once(__DIR__ . '/../conexion.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de torneo no válido.";
    exit;
}

$id = (int) $_GET['id'];

$sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo
        FROM torneos t
        INNER JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo
        WHERE t.pk_torneo = ? AND t.estatus = 1";

$stmt = $connect->prepare($sql);
$stmt->execute([$id]);
$torneo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$torneo) {
    echo "Torneo no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalle del Torneo - <?= htmlspecialchars($torneo['nom_torneo']) ?></title>
</head>
<body>
   

    <h1><?= htmlspecialchars($torneo['nom_torneo']) ?></h1>
    <p><strong>Tipo:</strong> <?= htmlspecialchars($torneo['nom_tipo']) ?></p>
    <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($torneo['descripcion'])) ?></p>
    <p><strong>Detalles:</strong> <?= nl2br(htmlspecialchars($torneo['detalles'])) ?></p>

    <?php if (!empty($torneo['img'])): ?>
        <p><strong>Imagen Principal:</strong><br>
        <img src="../../uploads/<?= urlencode($torneo['img']) ?>" alt="Imagen principal"></p>
    <?php endif; ?>

    <div class="imagenes-adicionales">
        <strong>Imágenes adicionales:</strong><br>
        <?php
        $sql_imgs = "SELECT img FROM img_torneos WHERE fk_torneo = ?";
        $stmt_imgs = $connect->prepare($sql_imgs);
        $stmt_imgs->execute([$torneo['pk_torneo']]);
        $imgs = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);

        if (count($imgs) === 0) {
            echo "<p>No hay imágenes adicionales.</p>";
        } else {
            foreach ($imgs as $img):
        ?>
                <img src="../../uploads/<?= ($img['img']) ?>" alt="Imagen adicional">
        <?php
            endforeach;
        }
        ?>
    </div>
</body>
</html>
