<?php
    include('../controller/proyecto/mostrar_proyecto.php');
    include_once('header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../css/proyecto.css">
    <title>Proyectos</title>
</head>
<body>

    <div class="con_volver">
        <a href="index.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Proyectos</h3>
    </div>

    <main class="proyectos">
        <?php foreach ($proyectos as $proyecto): ?>
            <a href="ver_detalles_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>" class="card">
                <img src="../img/<?= $proyecto['img_proyecto'] ?>" alt="Proyecto">
                <p><strong><?= $proyecto['nom_proyecto'] ?></strong></p>
                <p><?= $proyecto['detalles'] ?></p>
            </a>
        <?php endforeach; ?>
    </main>

</body>
</html>
