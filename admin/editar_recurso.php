<?php
include_once('header.php');
include('../controller/proyecto/buscar_recurso.php');

if (!isset($_GET['id'])) {
    header('Location: lista_recursos.php');
    exit;
}