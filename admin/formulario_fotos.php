<?php
include_once('header.php');

if (!isset($_GET['pk_proyecto'])) {
    header('Location: lista_proyectos.php');
    exit;
}

$id = $_GET['pk_proyecto'];
?>

<!-- Este formulario ya no es necesario, toda la gestión de imágenes se hará en editar_proyecto.php -->
<p style="text-align:center; color:#dc2626; font-weight:bold; margin-top:40px;">La gestión de imágenes adicionales ahora se realiza directamente en la edición del proyecto.</p>
<a href="editar_proyecto.php?pk_proyecto=<?= $id ?>" style="display:block; text-align:center; margin-top:20px; color:#fff; background:#dc2626; padding:12px 24px; border-radius:8px; text-decoration:none;">Volver a editar proyecto</a>