<?php
$proyectos = include('../controller/proyecto/mostrar_proyecto.php');
include_once('header.php'); 
?> 
<head>
    <link rel="stylesheet" href="../css/list_proyecto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <!-- <th>Eliminar</th> -->
            <th>Ver Imágenes</th> 
        </tr>

        <?php if (empty($proyectos)): ?>
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px;">
                    <strong>No hay proyectos disponibles actualmente.</strong><br>
                    Cuando se agreguen proyectos, aparecerán aquí.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($proyectos as $proyecto): ?>
            <tr>
                <td><img src="../uploads/<?= $proyecto['img_proyecto'] ?>" width="50px"></td>
                <td><?= $proyecto['nom_proyecto'] ?></td>
                <td><?= $proyecto['descripcion'] ?></td>
                <td><?= $proyecto['detalles'] ?></td>
                <td><a href="<?= $proyecto['url'] ?>"><?= $proyecto['url'] ?></a></td>
                <td class="estatus <?= $proyecto['estatus'] == 1 ? 'activo' : 'inactivo' ?>">
                    <?= $proyecto['estatus'] == 1 ? 'Activo' : 'Inactivo' ?>
                </td>
                <td>
                    <div class="botones-accion-en-linea">
                        <a class="btn-editar" href="editar_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>" title="Editar" >
                        <img src="../img/boton-editar.png" alt=""></a>
                    <a class="btn-eliminar" href="#" onclick="confirmAction(event, '<?= $proyecto['pk_proyecto'] ?>', '<?= $proyecto['estatus'] ?>')" title="Editar" title="Dar de baja">
                        <img src="../img/basura-bln.png" alt=""></a>
                    </div>
                </td>
                <!-- <td>
                    <a class="btn-eliminar" href="#" onclick="confirmDelete(event, '<?= $proyecto['pk_proyecto'] ?>')">
                        Eliminar <i class="fas fa-trash-alt"></i>
                    </a>
                </td> -->
               <td>
                    <a class="btn-ver-imagenes" href="imagenes_proyecto.php?id=<?= $proyecto['pk_proyecto'] ?>">Ver Imágenes</a>
                </td>

            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
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
    event.preventDefault(); 

    let actionText = currentStatus == 1 ? 'Desactivar' : 'Activar';
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
            
            window.location.href = `../controller/proyecto/baja_proyecto.php?id=${projectId}`;
        }
    });
}

function eliminarProyecto(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará el proyecto permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../php/borrar_proyecto.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                });
        }
    });
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('deleted')) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'El proyecto ha sido eliminado correctamente',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    } else if (urlParams.has('status_changed')) {
        const mensaje = urlParams.get('message');
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: `El proyecto ha sido ${mensaje} correctamente`,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    } else if (urlParams.has('error')) {
        const errorMsg = urlParams.get('error');
        let mensaje = 'Ha ocurrido un error';
        
        switch(errorMsg) {
            case 'no_id':
                mensaje = 'No se proporcionó el ID del proyecto';
                break;
            case 'not_found':
                mensaje = 'Proyecto no encontrado';
                break;
            case 'update_failed':
                mensaje = 'Error al actualizar el estado del proyecto';
                break;
            default:
                mensaje = 'Error: ' + errorMsg;
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: mensaje,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    }
};
</script>