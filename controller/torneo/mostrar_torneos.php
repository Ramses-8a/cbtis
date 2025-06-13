<?php
require_once(__DIR__ . '/../conexion.php');

$sql = $connect->prepare("SELECT * FROM torneos t inner join tipo_torneos tp on t.fk_tipo_torneo=tp.pk_tipo_torneo WHERE t.estatus = 1");

try {
    $sql->execute();

    if ($sql->rowCount() === 0) {
        $torneos = [];
    }

    $torneos = $sql->fetchAll(PDO::FETCH_ASSOC);



    return $torneos;

} catch (PDOException $e) {
    $torneos = [];
    // Log the error for debugging purposes, but don't output JSON directly
    // error_log("Error al traer los torneos: " . $e->getMessage());
}
