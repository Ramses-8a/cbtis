<?php
include_once('header.php'); 
$usuarios = include('../controller/usuario/mostrar_usuarios.php');
?> 
<head>
    <link rel="stylesheet" href="../css/list_proyecto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="con_volver">
    <a href="index.php" class="volver">
        <img src="../img/volver.webp" alt="Volver">
    </a>
    <h3>Usuarios Registrados</h3>
</div>
<div class="contenedor">
    <table>
        <tr>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                <td><?= $usuario['estatus'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                <td>
                    <div class="botones-accion-en-linea">
                        <a href="editar_usuario.php?pk_usuario=<?= $usuario['pk_usuario'] ?>" class="btn-editar">
                            <img src="../img/boton-editar.png" alt="Editar">
                        </a>
                        <a href="#" class="btn-eliminar" data-pk-usuario="<?= $usuario['pk_usuario'] ?>">
                            <img src="../img/basura-bln.png" alt="Eliminar">
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Función para eliminar usuario con confirmación
    $('.btn-eliminar').on('click', function(e) {
        e.preventDefault();
        const pkUsuario = $(this).data('pk-usuario');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/usuario/eliminar_usuario.php',
                    type: 'POST',
                    data: { pk_usuario: pkUsuario },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                Swal.fire(
                                    '¡Eliminado!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    data.message,
                                    'error'
                                );
                            }
                        } catch (e) {
                            Swal.fire(
                                'Error',
                                'Hubo un error al procesar la respuesta',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error',
                            'Hubo un error al comunicarse con el servidor',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
</body>