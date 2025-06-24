<?php
include_once('header.php');
include('../controller/tipo_lenguaje/mostrar_tipos.php');
?>

<div>
    <!-- Formulario -->
    <div>
        <h3>Crear Tipo de Lenguaje</h3>
        <div>
            <form id="tipoLenguajeForm" action="../controller/tipo_lenguaje/crear_tipo.php" method="POST">
                <label for="nom_lenguaje_input">Nombre del lenguaje</label>
                <input type="text" name="nom_lenguaje" id="nom_lenguaje_input" required>
                <input type="submit" value="Guardar">
            </form>
        </div>
    </div>
    
    <!-- Lista de tipos de lenguajes -->
    <div>
        <h3>Lista de Tipos de Lenguajes</h3>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tipos_lenguaje)): ?>
                    <?php foreach ($tipos_lenguaje as $tipo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tipo['nom_lenguaje']); ?></td>
                            <td><?php echo $tipo['estatus'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                            <td>
                                <button class="btn-editar" data-id="<?php echo $tipo['pk_lenguaje']; ?>" data-nombre="<?php echo htmlspecialchars($tipo['nom_lenguaje']); ?>">Editar</button>
                                <button class="btn-eliminar" data-id="<?php echo $tipo['pk_lenguaje']; ?>">Eliminar</button>
                                <button class="btn-cambiar-estatus" data-id="<?php echo $tipo['pk_lenguaje']; ?>" data-estatus="<?php echo $tipo['estatus']; ?>"><?php echo $tipo['estatus'] == 1 ? 'Desactivar' : 'Activar'; ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay tipos de lenguajes registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tipoLenguajeForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '../controller/tipo_lenguaje/crear_tipo.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        $('#nom_lenguaje_input').val(''); // Limpiar el campo
                        location.reload(); // Recargar la página
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error de conexión con el servidor',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    
    // Función para editar tipo de lenguaje
    $('.btn-editar').on('click', function() {
        var id = $(this).data('id');
        var nombreActual = $(this).data('nombre');
        
        Swal.fire({
            title: 'Editar Tipo de Lenguaje',
            html: '<label for="swal-input-nombre">Nombre del lenguaje:</label><br>' +
                  '<input id="swal-input-nombre" class="swal2-input" value="' + nombreActual + '" placeholder="Nombre del lenguaje">',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const nombre = document.getElementById('swal-input-nombre').value;
                if (!nombre) {
                    Swal.showValidationMessage('El nombre del lenguaje es requerido');
                    return false;
                }
                return { nombre: nombre };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar la petición AJAX para actualizar
                $.ajax({
                    url: '../controller/tipo_lenguaje/actualizar_tipo.php',
                    type: 'POST',
                    data: {
                        id: id,
                        nom_lenguaje: result.value.nombre
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Actualizado!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload(); // Recargar la página
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error de conexión con el servidor',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
    
    // Función para eliminar tipo de lenguaje
    $('.btn-eliminar').on('click', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Estás seguro de que deseas eliminar este tipo de lenguaje?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/tipo_lenguaje/eliminar_tipo.php',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload(); // Recargar la página
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error de conexión con el servidor',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
    
    // Función para cambiar estatus del tipo de lenguaje
    $('.btn-cambiar-estatus').on('click', function() {
        var id = $(this).data('id');
        var estatusActual = $(this).data('estatus');
        var nuevoEstatus = estatusActual == 1 ? 0 : 1;
        var accion = nuevoEstatus == 1 ? 'activar' : 'desactivar';
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Estás seguro de que deseas ' + accion + ' este tipo de lenguaje?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, ' + accion,
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/tipo_lenguaje/cambiar_estatus.php',
                    type: 'POST',
                    data: { 
                        id: id,
                        nuevo_estatus: nuevoEstatus
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Estatus cambiado!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload(); // Recargar la página
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error de conexión con el servidor',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
</script>