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
<<<<<<< HEAD
<<<<<<< HEAD
        <td><?= $proyecto['detalles'] ?></td>
        <td><?= $proyecto['url'] ?></td>
=======
        
>>>>>>> 9350ecf60b527ffa3775df22367c5196ef9b5ae7
=======
        <td><?= $proyecto['url'] ?></td>
>>>>>>> 0f464a43acfa4484f246d7c2a42f8c44d7aa38df
        <td>
            <a href="editar_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>">Editar</a>
            <a href="" onclick="if(confirm('¿Dar de baja este proyecto?')) window.location='baja_proyecto.php?id=<?= $proyecto['pk_proyecto'] ?>'">Dar de baja</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>