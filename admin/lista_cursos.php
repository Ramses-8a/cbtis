<?php
$cursos = include('../controller/cursos/mostrar_cursos.php');
include_once('header.php');

// The $alumnos variable should now be available from buscar_participantes.php if pk_torneo was set
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
            <th>Descripcion</th>
            <th>Tipo</th>
            <th>Lenguaje</th>
            <th>Url</th>
            <th>Estatus</th>
            <th>Acciones</th>
            <!-- <th>Eliminar</th> -->
        </tr>

        <?php if (empty($cursos)): ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    <strong>No hay proyectos disponibles actualmente.</strong><br>
                    Cuando se agreguen proyectos, aparecerán aquí.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($cursos as $curso): ?>
            <tr>
              <th>
                    <?php if (!empty($curso['img'])): ?>
                        <img src="../<?= $curso['img'] ?>" alt="Custom Image" style="width: 100px; height: 75px; object-fit: cover;">
                    <?php else: ?>
                        <?php
                        $youtube_link = $curso['link'];
                        $video_id = '';
                        $thumbnail_url = '';

                        // Extract video ID from various YouTube URL formats
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $youtube_link, $matches)) {
                            $video_id = $matches[1];
                            $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
                        }
                        ?>
                        <?php if (!empty($thumbnail_url)): ?>
                            <img src="<?= $thumbnail_url ?>" alt="YouTube Thumbnail" style="width: 100px; height: 75px; object-fit: cover;">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    <?php endif; ?>
                </th>
                <td><?= $curso['nom_curso'] ?></td>
                <td><?= $curso['descripcion'] ?></td>
                <td><?= $curso['nom_tipo'] ?></td>
                <td><?= $curso['nom_lenguaje'] ?></td>
                <td><a href="<?= $curso['link'] ?>"><?= $curso['link'] ?></a></td>
                 <td class="estatus <?= $curso['estatus'] == 1 ? 'activo' : 'inactivo' ?>">
                <?= $curso['estatus'] == 1 ? 'Activo' : 'Inactivo' ?>
                </td>
                <td>
                    <div class="botones-accion-en-linea">
                        <a class="btn-editar" href="editar_cursos.php?pk_curso=<?= $curso['pk_curso'] ?>" title="Editar" >
                        <img src="../img/boton-editar.png" alt=""></a>
                    <a class="btn-eliminar" href="#" onclick="confirmAction(event, '<?= $curso['pk_curso'] ?>', '<?= $curso['estatus'] ?>')"  title="Dar de baja">
                        <img src="../img/basura-bln.png" alt=""></a>
                    </div>
                </td>
                <!-- <td>
                    <a class="btn-eliminar" href="#" onclick="confirmDelete(event, '<?= $curso['pk_curso'] ?>')">
                        Eliminar <i class="fas fa-trash-alt"></i>
                    </a>
                </td> -->

            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>


</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
            window.location.href = `../controller/cursos/eliminar_curso.php?id=${projectId}`;
        }
    });
}
function confirmAction(event, courseId, currentStatus) {
    event.preventDefault(); // Previene la acción por defecto del enlace

    let actionText = currentStatus == 1 ? 'Desactivar' : 'Activar';
    let confirmMessage = `¿Estás seguro de ${actionText.toLowerCase()} este curso?`;

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
            window.location.href = `../controller/cursos/baja_curso.php?id=${courseId}`;
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
            text: 'El curso ha sido eliminado correctamente',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    } else if (urlParams.has('status_changed')) {
        const mensaje = urlParams.get('message');
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: `El curso ha sido ${mensaje} correctamente`,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    } else if (urlParams.has('error')) {
        const errorMsg = urlParams.get('error');
        let mensaje = 'Ha ocurrido un error';
        
        switch(errorMsg) {
            case 'no_id':
                mensaje = 'No se proporcionó el ID del curso';
                break;
            case 'not_found':
                mensaje = 'Curso no encontrado';
                break;
            case 'update_failed':
                mensaje = 'Error al actualizar el estado del curso';
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