<?php
include('../controller/proyecto/mostrar_proyecto.php');
include_once('header.php'); 
?> 
<head>
    <link rel="stylesheet" href="../css/list_proyecto.css">
</head>
<body>
   <div class="contenedor">
     <table>
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Detalles</th>
        <th>Url</th>
        <th>Estatus</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($proyectos as $proyecto): ?>
    <tr>
        <td><img src="../img/<?= $proyecto['img_proyecto'] ?>" width="50px"></td>
        <td><?= $proyecto['nom_proyecto'] ?></td>
        <td><?= $proyecto['descripcion'] ?></td>
        <td><?= $proyecto['detalles'] ?></td>
        <td><?= $proyecto['url'] ?></td>
        <td><?= $proyecto['estatus'] == 1 ? 'activo' : 'baja' ?></td>
        <td>
            <a class="btn-editar" href="editar_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>">Editar</a>
            <a class="btn-eliminar" href="" onclick="if(confirm('¿Dar de baja este proyecto?')) window.location='baja_proyecto.php?id=<?= $proyecto['pk_proyecto'] ?>'">Dar de baja</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

</body>

<script>
function confirmAction(event, projectId, currentStatus) {
    event.preventDefault(); // Previene la acción por defecto del enlace

    let actionText = currentStatus == 1 ? 'Dar de baja' : 'Dar de alta';
    let confirmMessage = `¿Estás seguro de ${actionText.toLowerCase()} este proyecto?`;

    Swal.fire({
        title: 'Confirmar Acción',
        text: confirmMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, redirige a la URL de acción
            window.location.href = `../controller/proyecto/baja_proyecto.php?id=${projectId}`;
        }
    });
}
</script>
