<?php
require_once 'controller/conexion.php';
require_once 'header.php';

$tipo = $_GET['tipo'] ?? '';

if ($tipo) {
    $sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo
            FROM torneos t
            INNER JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo
            WHERE t.estatus = 1 AND tt.estatus = 1 AND LOWER(tt.nom_tipo) = LOWER(?)
            ORDER BY t.pk_torneo DESC";

    $stmt = $connect->prepare($sql);
    $stmt->execute([$tipo]);
} else {
    $sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo
            FROM torneos t
            INNER JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo
            WHERE t.estatus = 1 AND tt.estatus = 1
            ORDER BY t.pk_torneo DESC";

    $stmt = $connect->prepare($sql);
    $stmt->execute();
}
 

$torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Torneos</title>
    <link rel="stylesheet" href="css/proyecto.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="con_volver">
    <a href="index.php" class="volver">
        <img src="img/regresar.png" alt="Volver">
    </a>
    <h3>Torneos</h3>
</div><?php if ($tipo): ?>
    <h1 style="text-align: center; margin-top: 30px;">Lista de Torneos de <?= htmlspecialchars(ucfirst($tipo)) ?></h1>
<?php else: ?>
    <h1 style="text-align: center; margin-top: 30px;">Lista de Todos los Torneos</h1>
<?php endif; ?>

<div class="proyectos">
    <?php if (count($torneos) > 0): ?>
        <?php foreach ($torneos as $torneo): ?>
            <div class="card">
                <a href="detalle_torneo.php?id=<?= urlencode($torneo['pk_torneo']) ?>" style="text-decoration: none; color: inherit;">
                    <?php if (!empty($torneo['img'])): ?>
                        <img src="uploads/<?= htmlspecialchars($torneo['img']) ?>" alt="Imagen principal">
                    <?php endif; ?>
                    <p><strong class="nombre-proyecto"><?= htmlspecialchars($torneo['nom_torneo']) ?></strong></p>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="padding: 20px;">No se encontraron torneos para este tipo.</p>
    <?php endif; ?>
</div>


