<?php
require_once(__DIR__ . '/../conexion.php');

try {
    $stmt = $connect->prepare('SELECT * FROM tipo_recursos');
    $stmt->execute();

    $tipo_recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $tipo_recursos; 
} catch (\Throwable $th) {
    //throw $th;
}