<?php
include_once('header.php');
?>


<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Crear Usuario</title>
</head>

<div class="con_volver">
    <a href="javascript:history.back()" class="volver">
        <img src="../img/volver.webp" alt="Volver">
    </a>
    <h3>Usuarios</h3>
</div>

<form id="formCrearUsuario" class="form-proyectos">
    <div>
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
    </div>

    <div>
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" required>
    </div>

    <div>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div>
        <label for="password_veri">Confirmar contraseña:</label>
        <input type="password" id="password_veri" name="password_veri" required>
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Crear Usuario</button>
        <button class="cancelar" type="button" onclick="window.location.href='index.php'">Cancelar</button>
    </div>
</form>

<script>
    document.getElementById('formCrearUsuario').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch('../controller/usuario/crear_usuario.php', {
                method: 'POST',
                body: formData
            });

            const res = await response.json();

            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: res.message,
                    confirmButtonColor: '#9d0707'
                }).then(() => {
                    this.reset(); 
                });
            } else if (res.status === 'warning') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: res.message,
                    confirmButtonColor: '#9d0707'
                });
            } else if (res.status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message,
                    confirmButtonColor: '#9d0707'
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: res.message || 'Respuesta desconocida del servidor',
                    confirmButtonColor: '#9d0707'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al procesar la solicitud.',
                confirmButtonColor: '#9d0707'
            });
        }
    });
</script>

<style>
    .con_volver {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        background-color: white;
    }

    .con_volver .volver img {
        width: 28px;
        height: 28px;
        object-fit: contain;
        cursor: pointer;
    }

    .con_volver h3 {
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0;
    }
</style>

