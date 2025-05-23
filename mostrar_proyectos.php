<?php
    include('controller/mostrar_proyecto.php');
    include_once('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Proyectos de Programación</h1><br>
    <div class="proyectos">
         <?php foreach ($proyectos as $proyecto): ?>
        <img src="img/<?=$proyecto['img_proyecto']?>" alt="" width="250px">    
        <h1><?=$proyecto['nom_proyecto']?></h1>
        <h2><?=$proyecto['descripcion']?></h2>
        <h3><?=$proyecto['detalles']?></h3>
        <?php endforeach; ?>
    </div>
   
</body>
</html>

