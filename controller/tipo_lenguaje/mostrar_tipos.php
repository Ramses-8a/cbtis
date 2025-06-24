<?php
require_once(__DIR__ . '/../conexion.php');

try {
    $stmt = $connect->prepare('SELECT * FROM lenguajes');
    $stmt->execute();
    $tipos_lenguaje = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = 'Error al obtener los tipos de recurso: ' . $e->getMessage();
}