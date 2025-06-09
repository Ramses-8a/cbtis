<?php
include('../controller/torneo/mostrar_torneos.php');
include_once('header.php'); 
?>
<head>
    <link rel="stylesheet" href="../css/list_proyecto.css">
</head>
<div class="contenedor">
<table>
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Descripcion</th>
        <th>Detalles</th>
        <th>Estatus</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($torneos as $torneo): ?>
    <tr>
        <td><img src="../img/<?= $torneo['img'] ?>" width="50px"></td>
        <td><?= $torneo['nom_torneo'] ?></td>
        <td><?= $torneo['fk_tipo_torneo'] ?></td>
        <td><?= $torneo['descripcion'] ?></td>
        <td><?= $torneo['detalles'] ?></td>
        <td><?= $torneo['estatus']?></td>
        <td>

            <a class="btn-editar" href="editar_torneo.php?pk_torneo=<?= $torneo['pk_torneo'] ?>">Editar</a>
            <a class="btn-eliminar" href="" onclick="if(confirm('¿Dar de baja este torneo?')) window.location='baja_torneo.php?id=<?= $torneo['pk_torneo'] ?>'">Dar de baja</a>
            <a class="btn-editar" href="lista_participantes.php?pk_torneo=<?= $torneo['pk_torneo']?>">Ver particiapantes</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>