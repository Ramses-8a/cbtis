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
        <th>Eliminar</th>
    </tr>

    <?php if (empty($torneos)): ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    <strong>No hay torneos disponibles actualmente.</strong><br>
                    Cuando se agreguen proyectos, aparecerán aquí.
                </td>
            </tr>
        <?php else: ?>
    <?php foreach ($torneos as $torneo): ?>
    <tr>
        <td><img src="../uploads/<?= $torneo['img'] ?>" width="50px"></td>
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
        <td>
            <a class="btn-eliminar" href="#" onclick="confirmDelete(event, '<?= $torneo['pk_torneo'] ?>')">
                Eliminar <i class="fas fa-trash-alt"></i>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</table>
</div>

<script>

// ... existing code ...
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
            window.location.href = `../controller/torneo/eliminar_torneo.php?id=${projectId}`;
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

<style>table tr td[colspan="8"] {
    background-color: #f9f9f9;
    color: #555;
    font-size: 16px;
}
</style>