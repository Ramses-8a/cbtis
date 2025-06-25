<?php
include_once('header.php');
include('../controller/tipo_recursos/mostrar_tipo_recursos.php');
?>

<div>
    <div>
        <div>
            <form id="tipoRecursoForm" action="../controller/tipo_recursos/crear_tipo.php" method="POST">
                <label for="nom_tipo">Nombre del tipo de recurso</label>
                <input type="text" name="nom_tipo" id="nom_tipo" required>
                <input type="submit" value="Guardar">
            </form>
        </div>
        <div>
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
                                <td><?= $tipo_recurso['estatus'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                                <td>
                                    <button class="editar-btn" data-id="<?= $tipo_recurso['pk_tipo_recurso'] ?>" data-nombre="<?= htmlspecialchars($tipo_recurso['nom_tipo']) ?>">Editar</button>
                                    <button class="cambiar-estatus-btn" data-id="<?= $tipo_recurso['pk_tipo_recurso'] ?>" data-estatus="<?= $tipo_recurso['estatus'] ?>">
                                        <?= $tipo_recurso['estatus'] == 1 ? 'Desactivar' : 'Activar' ?>
                                    </button>
                                    <button class="eliminar-btn" data-id="<?= $tipo_recurso['pk_tipo_recurso'] ?>">Eliminar</button>
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
