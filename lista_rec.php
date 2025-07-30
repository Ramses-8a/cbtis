<?php 
include_once 'header.php'; 
require_once 'controller/recursos/mostrar_recursos.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($recurso['nom_recurso']) ?> - Detalle</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/list_rec.css">
</head>
<body>
<div class="con_volver">
        <a href="mostrar_recursos.php" class="volver">
            <img src="img/regresar.png" alt="Volver">
        </a>
        <h3>Volver</h3>
    </div>

<div class="container">
    <!-- <h1>Imágenes relacionadas</h1> -->

    <div class="recurso-img">
        <?php if (!empty($recurso['img'])): ?>
            <img src="uploads/<?= htmlspecialchars($recurso['img']) ?>" alt="Imagen de <?= htmlspecialchars($recurso['nom_recurso']) ?>">
        <?php else: ?>
            <p>Sin imagen disponible</p>
        <?php endif; ?>
    </div>

    <!-- <div class="detalle"><span>Nombre:</span>htmlspecialchars($recurso['nom_recurso']) ?></div> -->
    <div class="detalle"><?= nl2br(htmlspecialchars($recurso['descripcion'])) ?></div>
    <!-- <div class="detalle"><span>Tipo:</span>  htmlspecialchars($recurso['nom_tipo']) ?></div> -->

    <?php if (!empty($recurso['url'])): ?>
        <a href="<?= htmlspecialchars($recurso['url']) ?>" class="boton" target="_blank" rel="noopener noreferrer">Visitar</a>
    <?php endif; ?>

</div>

</body>
</html>

