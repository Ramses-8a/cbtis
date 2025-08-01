<?php
require_once(__DIR__ . '/../conexion.php');

try {
    $stmt = $connect->prepare('SELECT * FROM tipo_recursos');
    $stmt->execute();
    $tipo_recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = 'Error al obtener los tipos de recurso: ' . $e->getMessage();
}