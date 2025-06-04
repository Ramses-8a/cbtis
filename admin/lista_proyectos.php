<?php
include('../controller/proyecto/mostrar_proyecto.php');
include_once('header.php'); 
?>
<table>
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Detalles</th>
        <th>Url</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($proyectos as $proyecto): ?>
    <tr>
        <td><img src="../img/<?= $proyecto['img_proyecto'] ?>" width="50px"></td>
        <td><?= $proyecto['nom_proyecto'] ?></td>
        <td><?= $proyecto['descripcion'] ?></td>
        <td><?= $proyecto['detalles'] ?></td>
        <td><?= $proyecto['url'] ?></td>
        <td>
            <a href="editar_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>">Editar</a>
            <a href="baja_proyecto.php?id=<?= $proyecto['pk_proyecto'] ?>" onclick="return confirm('¿Dar de baja este proyecto?')">Dar de baja</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
