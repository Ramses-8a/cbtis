<?php
require_once(__DIR__ . '/../conexion.php');

try {
    $stmt = $connect->prepare("SELECT r.*, tr.nom_tipo 
                              FROM recursos r 
                              LEFT JOIN tipo_recursos tr ON r.fk_tipo_recurso = tr.pk_tipo_recurso");
    $stmt->execute();
    $recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$recursos) {
        $recursos = [];
    }
    
    return $recursos;
    
} catch (PDOException $e) {
    die(json_encode([
        "status" => "error",
        "message" => "Error al obtener los recursos",
        "error" => $e->getMessage()
    ]));
}