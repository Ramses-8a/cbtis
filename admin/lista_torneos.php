<?php
include('../controller/torneo/mostrar_torneos.php');
include_once('header.php'); 
?>
<head>
    <link rel="stylesheet" href="..css">
</head>

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
            <a href="editar_torneo.php?pk_torneo=<?= $torneo['pk_torneo'] ?>">Editar</a>
            <a href="" onclick="if(confirm('¿Dar de baja este torneo?')) window.location='baja_torneo.php?id=<?= $torneo['pk_torneo'] ?>'">Dar de baja</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>