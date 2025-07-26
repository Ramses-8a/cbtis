<?php
include_once('header.php');

if (!isset($_SESSION['fk_tipo_usuario']) || $_SESSION['fk_tipo_usuario'] != 1) {
    header('Location: index.php');
    exit();
}

$usuarios = include('../controller/usuario/mostrar_usuarios.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="../css/list_proyecto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .btn-deshabilitado {
            cursor: not-allowed;
            opacity: 0.5;
            display: inline-block;
            padding: 5px;
        }
    </style>
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
                            <a href="editar_usuario.php?pk_usuario=<?= $usuario['pk_usuario'] ?>" class="btn-editar" title="Editar">
                                <img src="../img/boton-editar.png" alt="Editar">
                            </a>
                            <?php if ($usuario['pk_usuario'] != $_SESSION['usuario_id']): ?>
                                <a href="#" class="btn-eliminar" data-pk-usuario="<?= $usuario['pk_usuario'] ?>" title="Dar de baja">
                                    <img src="../img/basura-bln.png" alt="Eliminar">
                                </a>
                            <?php else: ?>
                                <span class="btn-deshabilitado" title="No puedes desactivar tu propia cuenta">
                                    <img src="../img/basura-bln.png" alt="Eliminar deshabilitado">
                                </span>
                            <?php endif; ?>
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
        $('.btn-eliminar').on('click', function(e) {
            e.preventDefault();
            const pkUsuario = $(this).data('pk-usuario');
            const row = $(this).closest('tr');
            const currentStatus = row.find('td:nth-child(3)').text().trim() === 'Activo' ? 1 : 0;
            
            Swal.fire({
                title: currentStatus == 1 ? '¿Desactivar usuario?' : '¿Activar usuario?',
                text: currentStatus == 1 ? 'El usuario perderá acceso al sistema' : 'El usuario podrá acceder nuevamente',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: currentStatus == 1 ? 'Sí, desactivar' : 'Sí, activar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controller/usuario/eliminar_usuario.php',
                        type: 'POST',
                        data: { pk_usuario: pkUsuario },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'error') {
                                Swal.fire('Error', response.message, 'error');
                            } else {
                                Swal.fire('Éxito', response.message, 'success').then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Error en la comunicación con el servidor', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>