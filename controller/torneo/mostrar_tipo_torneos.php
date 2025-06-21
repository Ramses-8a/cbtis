<?php
require_once(__DIR__ . '/../conexion.php');

function getTiposTorneo($connect) {
    $sql = $connect->prepare("SELECT pk_tipo_torneo, nom_tipo FROM tipo_torneos");

    try {
        $sql->execute();
        $tipos_torneo = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $tipos_torneo;
    } catch (PDOException $e) {
        // Log the error for debugging purposes
        error_log("Error al traer los tipos de torneo: " . $e->getMessage());
        return [];
    }
}
?>
?>