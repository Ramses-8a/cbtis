<?php
    include('controller/mostrar_proyecto.php')
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php foreach ($proyectos as $proyecto): ?>
        <h1><?=$proyecto['nom_proyecto']?></h1>
        <h1><?=$proyecto['descripcion']?></h1>
        <h1><?=$proyecto['detalles']?></h1>
        <img src="img/<?=$proyecto['img_proyecto']?>" alt="">    
        <?php endforeach; ?>
</body>
</html>