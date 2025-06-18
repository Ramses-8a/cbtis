<?php
require_once(__DIR__ . '/../conexion.php');


$sql = $connect->prepare("SELECT t.*, tt.nom_tipo, (SELECT COUNT(*) FROM alumno_torneos WHERE fk_torneo = t.pk_torneo) AS total_participantes FROM torneos t JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo");


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
