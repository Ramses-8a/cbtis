<?php
include_once('header.php'); 
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
            <th>Nombre</th>
            <th>Correo</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
            <tr>
                <td></td>
                <td></td>
                <td ></td>
                <td>
                    <div class="botones-accion-en-linea">
                        <a class="btn-editar">
                        <img src="../img/boton-editar.png" alt=""></a>
                    <a class="btn-eliminar" >
                        <img src="../img/basura-bln.png" alt=""></a>
                    </div>
                </td>
            </tr>
    </table>
</div>

</body>