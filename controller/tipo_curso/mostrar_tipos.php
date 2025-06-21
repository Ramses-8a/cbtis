<?php
require_once('../conexion.php');

try {
    $sql = "SELECT pk_tipo_curso, nom_tipo, estatus FROM tipo_cursos ORDER BY pk_tipo_curso DESC";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $tipos
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los tipos de curso: ' . $e->getMessage()
    ]);
}
?>