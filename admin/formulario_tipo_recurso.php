<?php
include_once('header.php');
include('../controller/tipo_recursos/mostrar_tipo_recursos.php');
?>

<head>
    <link rel="stylesheet" href="../css/tipos_cambios.css">
</head>
<div class="con_volver">
        <a href="formulario_recursos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Regresar</h3>
    </div>
<div class="contenedor-flex">
    <!-- Formulario -->
    <div class="formulario">
        <h3>Crear Tipo de Recurso</h3>
        <form id="tipoRecursoForm" action="../controller/tipo_recursos/crear_tipo.php" method="POST">
            <label for="nom_tipo">Nombre del tipo de recurso</label>
            <input type="text" name="nom_tipo" id="nom_tipo" required>
            <input type="submit" value="Guardar">
        </form>
    </div>

    <!-- Tabla -->
    <div class="tabla">
        <h3>Lista de Tipos de Recurso</h3>
        <table>
            <thead>
                <tr>
                    <th>Nombre del Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tipo_recursos)): ?>
                    <?php foreach ($tipo_recursos as $tipo_recurso): ?>

                        <tr>
                            <td><?= htmlspecialchars($tipo_recurso['nom_tipo']) ?></td>
                            <td><?= $tipo_recurso['estatus'] == 1 ? 'Activo' : 'De baja' ?></td>
                            <td>
                    <div class="botones-accion-en-linea">
                        <a class="btn-editar" href="editar_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>">
                        <img src="../img/boton-editar.png" alt=""></a>
                    <a class="btn-eliminar" href="#" onclick="confirmAction(event, '<?= $proyecto['pk_proyecto'] ?>', '<?= $proyecto['estatus'] ?>')">
                        <img src="../img/basura-bln.png" alt=""></a>
                    </div>
                </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay tipos de recurso registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Crear tipo de recurso
        $('#tipoRecursoForm').submit(function(event) {
            event.preventDefault();
            
            var formData = $(this).serialize();
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "¡Éxito!",
                            text: response.message,
                            icon: "success"
                        }).then(() => {
                            $('#nom_tipo').val('');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error",
                        text: "Hubo un problema al intentar crear el tipo de recurso.",
                        icon: "error"
                    });
                }
            });
        });

        // Editar tipo de recurso
        $(document).on('click', '.editar-btn', function(event) {
            event.preventDefault();
            var id_tipo = $(this).data('id');
            var nom_tipo_actual = $(this).data('nombre');

            Swal.fire({
                title: 'Editar Tipo de Recurso',
                input: 'text',
                inputLabel: 'Nombre del tipo',
                inputValue: nom_tipo_actual,
                showCancelButton: true,
                confirmButtonText: 'Guardar Cambios',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) {
                        return '¡Necesitas escribir algo!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var nuevo_nom_tipo = result.value;
                    $.ajax({
                        type: 'POST',
                        url: '../controller/tipo_recursos/actualizar_tipo.php',
                        data: { id: id_tipo, nom_tipo: nuevo_nom_tipo },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    '¡Actualizado!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error',
                                'Hubo un problema al intentar actualizar el recurso.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Cambiar estatus
        $(document).on('click', '.cambiar-estatus-btn', function(event) {
            event.preventDefault();
            var id_tipo = $(this).data('id');
            var estatus_actual = $(this).data('estatus');
            var accion = estatus_actual == 1 ? 'desactivar' : 'activar';
                var titulo = estatus_actual == 1 ? '¿Desactivar?' : '¿Activar?';
                var texto = estatus_actual == 1 ? '¿Estás seguro de que quieres desactivar este tipo de recurso?' : '¿Estás seguro de que quieres activar este tipo de recurso?';

            Swal.fire({
                title: titulo,
                text: texto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ' + accion + '!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '../controller/tipo_recursos/cambiar_estatus.php',
                        data: { id: id_tipo, estatus: estatus_actual },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    '¡Actualizado!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error',
                                'Hubo un problema al intentar cambiar el estatus.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Eliminar tipo de recurso
        $(document).on('click', '.eliminar-btn', function(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id_tipo = $(this).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '../controller/tipo_recursos/eliminar_tipo.php',
                        data: { id: id_tipo },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    '¡Eliminado!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error',
                                'Hubo un problema al intentar eliminar el recurso.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
