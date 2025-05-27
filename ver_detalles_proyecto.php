<?php
require_once 'controller/conexion.php';

if (!isset($_GET['pk_proyecto'])) {
    exit("Proyecto no especificado.");
}

$pk_proyecto = $_GET['pk_proyecto'];

$stmt = $connect->prepare("SELECT * FROM proyectos WHERE pk_proyecto = ?");
$stmt->execute([$pk_proyecto]);
$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proyecto) {
    exit("Proyecto no encontrado.");
}

$stmt_imgs = $connect->prepare("SELECT img FROM img_proyectos WHERE fk_proyecto = ?");
$stmt_imgs->execute([$pk_proyecto]);
$imagenes = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $proyecto['nom_proyecto'] ?></title>
    <link rel="stylesheet" href="css/proyecto.css">
</head>
<body>

    <div class="con_volver">
        <a href="index.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3><?= $proyecto['nom_proyecto'] ?></h3>
    </div>

    <main class="detalle-proyecto">
        <h2><?= $proyecto['nom_proyecto'] ?></h2>
        <p><strong>Descripción:</strong> <?= $proyecto['descripcion'] ?></p>
        <p><strong>Detalles:</strong> <?= $proyecto['detalles'] ?></p>

        <h3>Imágenes relacionadas</h3>
        <div class="galeria-imagenes">
            <?php foreach ($imagenes as $img): ?>
                <img src="img/<?= $img['img'] ?>" alt="Imagen del proyecto" width="200">
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
