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
        <th>Eliminar</th> <!-- Nueva columna para eliminar -->
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
            <!-- Aquí se ha corregido el onclick para usar la función confirmAction -->
            <a class="btn-eliminar" href="#" onclick="confirmAction(event, '<?= $proyecto['pk_proyecto'] ?>', '<?= $proyecto['estatus'] ?>')"><?= $proyecto['estatus'] == 1 ? 'Dar de baja' : 'Dar de alta' ?></a>
        </td>
        <td>
            <a class="btn-eliminar" href="#" onclick="confirmDelete(event, '<?= $proyecto['pk_proyecto'] ?>')">
                Eliminar <i class="fas fa-trash-alt"></i> <!-- Botón de eliminar con ícono -->
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

</body>

<script>

function confirmDelete(event, projectId) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../controller/proyecto/eliminar_proyecto.php?id=${projectId}`;
        }
    });
}

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