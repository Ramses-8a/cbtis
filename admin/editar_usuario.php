<?php
include_once('header.php');
include_once('../controller/conexion.php');
include_once('../controller/usuario/buscar_usuario.php');

if (!isset($_GET['pk_usuario'])) {
    header('Location: lista_usuarios.php');
    exit();
}

$pk_usuario = $_GET['pk_usuario'];
$usuario = include('../controller/usuario/buscar_usuario.php');

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

if (!isset($_SESSION['fk_tipo_usuario']) || $_SESSION['fk_tipo_usuario'] != 1) {
    header('Location: index.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../css/form_editar.css">
</head>

<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        <form id="formEditarUsuario" action="../controller/usuario/actualizar_usuario.php" method="POST">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>

            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
            </div>

            <div class="form-group">
                <label for="nueva_password">Nueva Contraseña(Opcional):</label>
                <input type="password" id="nueva_password" name="nueva_password">
            </div>
            <div class="form-group">
                <label for="confirmar_password">Confirmar Nueva Contraseña(Opcional):</label>
                <input type="password" id="confirmar_password" name="confirmar_password">
            </div>
            <input type="hidden" name="pk_usuario" value="<?= htmlspecialchars($usuario['pk_usuario']) ?>">
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#formEditarUsuario').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                '¡Éxito!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.href = 'lista_usuarios.php';
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error',
                            'Hubo un error al comunicarse con el servidor.',
                            'error'
                        );
                    }
                });
            });
        });
    </script>
</body>

</html>