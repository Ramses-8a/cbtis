<?php
include_once('header.php');
?>
<head>
     <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Usuarios</title>
</head>
<div class="con_volver">
            <a href="index.php" class="volver">
                <img src="../img/volver.webp" alt="Volver">
            </a>
            <h3>Usuarios</h3>
</div>

<form id="formUsuario" enctype="multipart/form-data" class="form-proyectos">
    <div>
        <label for="nom_proyecto">Nombre completo:</label>
        <input type="text" id="nom_proyecto" name="nom_proyecto" placeholder="Escribe el nombre del usuario" required>
    </div>

    <div>
        <label for="email" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@dominio.com" required>
    </div>

    <div>
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres" required minlength="8">
    </div>

    <div>
        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repite tu contraseña" required minlength="8">
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Guardar</button>
        <button class="cancelar" type="submit" onclick="window.location.href='index.php'">Cancelar</button>
    </div>
</form>                     
</body>
</html>