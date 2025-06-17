<?php
    include('controller/proyecto/mostrar_proyecto.php');
    include_once('header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/proyecto.css">
    <title>Proyectos</title>
</head>
<body>

    <div class="con_volver">
        <a href="index.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3>Proyectos</h3>

        <div>
            <input type="text" placeholder="Buscador">
        </div>
    </div>

    <main class="proyectos">
    <?php foreach ($proyectos as $proyecto):
            // Add this condition to check the status
            if (isset($proyecto['estatus']) && $proyecto['estatus'] == 1):
        ?>
            <a href="ver_detalles_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>" class="card">
                <img src="uploads/<?= $proyecto['img_proyecto'] ?>" alt="Proyecto">
                <p><strong><?= $proyecto['nom_proyecto'] ?></strong></p>
            </a>
            <?php 
            endif; // Close the if condition
            endforeach; 
        ?>
    </main>

</body>
</html>


