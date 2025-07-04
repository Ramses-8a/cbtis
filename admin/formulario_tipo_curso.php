<?php
include_once('header.php');
include('../controller/tipo_curso/mostrar_tipo.php');
?>
<head>
    <link rel="stylesheet" href="../css/tipos_cambios.css">
</head>
<div class="con_volver">
        <a href="formulario_cursos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Regresar</h3>
    </div>
<div class="contenedor-flex">
    <!-- Formulario -->
    <div class="formulario">
        <h3>Crear Tipo de Curso</h3>
        <form id="tipoCursoForm" action="../controller/tipo_curso/crear_tipo.php" method="POST">
            <label for="nom_tipo_input">Nombre del tipo de curso</label>
            <input type="text" name="nom_tipo" id="nom_tipo_input" required>
            <input type="submit" value="Guardar">
        </form>
    </div>
    
    <!-- Lista de tipos de curso -->
    <div class="tabla">
        <h3>Lista de Tipos de Curso</h3>
        <table id="tiposCursoTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tipos_curso)): ?>
                    <?php foreach ($tipos_curso as $tipo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tipo['nom_tipo']); ?></td>
                            <td><?php echo $tipo['estatus'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
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
                        <td colspan="3">No hay tipos de curso registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#tipoCursoForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '../controller/tipo_curso/crear_tipo.php',
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
                        $('#nom_tipo_input').val(''); // Limpiar el campo
                        location.reload(); // Recargar la página para mostrar el nuevo tipo
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
    
    // Event listener para el botón cambiar estatus
    $(document).on('click', '.cambiar-estatus-btn', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var estatusActual = $(this).data('estatus');
        var nuevoEstatus = estatusActual == 1 ? 0 : 1;
        var accion = estatusActual == 1 ? 'desactivar' : 'activar';
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Deseas ' + accion + ' el tipo de curso "' + nombre + '"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ' + accion,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar el cambio de estatus
                $.ajax({
                    type: 'POST',
                    url: '../controller/tipo_curso/cambiar_estatus.php',
                    data: { 
                        id: id,
                        nuevo_estatus: nuevoEstatus
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload(); // Recargar la página
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al intentar cambiar el estatus del tipo de curso.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
    
    // Event listener para el botón editar
    $(document).on('click', '.editar-btn', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        
        Swal.fire({
            title: 'Editar Tipo de Curso',
            html: '<input id="swal-input-nombre" class="swal2-input" placeholder="Nombre del tipo de curso" value="' + nombre + '">',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: () => {
                const nuevoNombre = document.getElementById('swal-input-nombre').value;
                if (!nuevoNombre) {
                    Swal.showValidationMessage('Por favor ingresa un nombre');
                }
                return nuevoNombre;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar la actualización del tipo de curso
                $.ajax({
                    type: 'POST',
                    url: '../controller/tipo_curso/actualizar_tipo.php',
                    data: { 
                        id: id,
                        nom_tipo: result.value
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload(); // Recargar la página
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error details:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText,
                            statusCode: xhr.status
                        });
                        
                        let errorMessage = 'Hubo un problema al intentar actualizar el tipo de curso.';
                        
                        if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch (e) {
                                errorMessage += ' Respuesta del servidor: ' + xhr.responseText;
                            }
                        }
                        
                        errorMessage += ' (Status: ' + xhr.status + ', Error: ' + error + ')';
                        
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
        
        // Event listener para el botón eliminar
        $(document).on('click', '.eliminar-btn', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas eliminar el tipo de curso "' + nombre + '"? Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Realizar la eliminación
                    $.ajax({
                        type: 'POST',
                        url: '../controller/tipo_curso/eliminar_tipo.php',
                        data: { id: id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: response.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload(); // Recargar la página
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un problema al intentar eliminar el tipo de curso.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    });
</script>