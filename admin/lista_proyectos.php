<?php
include('../controller/proyecto/mostrar_proyecto.php');
include_once('header.php'); 
?> 
<body>
   <div class="contenedor">
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

            <a class="btn-editar" href="editar_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>">Editar</a>
            <a class="btn-eliminar" href="" onclick="if(confirm('¿Dar de baja este proyecto?')) window.location='baja_proyecto.php?id=<?= $proyecto['pk_proyecto'] ?>'">Dar de baja</a>

        </td>
    </tr>
    <?php endforeach; ?>
</table>
