<?php
include '../controller/conexion.php';


$sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo
        FROM torneos t
        INNER JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo
        WHERE t.estatus = 1
        ORDER BY t.pk_torneo DESC";

$stmt = $connect->prepare($sql);
$stmt->execute();
$torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Torneos </title>
</head>
<body>
    <h1>Lista de Torneos </h1>

    <?php foreach ($torneos as $torneo): ?>
        <div class="torneo">
            <h2><?= ($torneo['nom_torneo']) ?></h2>
            <p><strong>Tipo:</strong> <?= ($torneo['nom_tipo']) ?></p>
            <p><strong>Descripción:</strong> <?= ($torneo['descripcion']) ?></p>
            <p><strong>Detalles:</strong> <?= ($torneo['detalles']) ?></p>

            <?php if (!empty($torneo['img'])): ?>
                <p><strong>Imagen Principal:</strong><br>
                    <img src="../uploads/<?= ($torneo['img']) ?>" alt="Imagen principal">
                </p>
            <?php endif; ?>

            <div>
                <strong>Imágenes adicionales:</strong><br>
                <?php
                $sql_imgs = "SELECT img FROM img_torneos WHERE fk_torneo = ?";
                $stmt_imgs = $connect->prepare($sql_imgs);
                $stmt_imgs->execute([$torneo['pk_torneo']]);
                $imgs = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);

                foreach ($imgs as $img):
                ?>
                    <img src="../uploads/<?= ($img['img']) ?>" alt="Imagen adicional">
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
