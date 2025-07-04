<?php
require_once(__DIR__ . '/../conexion.php');

if (!isset($_GET['pk_torneo'])) {
    exit("Torneo no especificado.");
}

$pk_torneo = $_GET['pk_torneo'];

$stmt = $connect->prepare("SELECT * FROM alumno_torneos WHERE fk_torneo =?");
$stmt->execute([$pk_torneo]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si no hay alumnos, simplemente continuamos con un array vacío
// El mensaje se mostrará en la vista

// No need to return $alumnos here as it's included directly in lista_participantes.php
// The variable $alumnos will be available in the scope where this file is included.