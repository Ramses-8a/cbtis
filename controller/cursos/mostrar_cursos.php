<?php
require_once(__DIR__ . '/../conexion.php');

$sql = $connect->prepare("SELECT c.*, tc.nom_tipo, l.nom_lenguaje FROM cursos c LEFT JOIN tipo_cursos tc ON c.fk_tipo_curso = 
tc.pk_tipo_curso LEFT JOIN lenguajes l ON c.fk_lenguaje = l.pk_lenguaje");

try {
    $sql->execute();
    $cursos = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Siempre retorna un arreglo (puede estar vacío)
    return $cursos;

} catch (PDOException $e) {
    // Si hay error real de conexión o consulta, sí mostramos mensaje de error
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al traer los proyectos",
        "error" => $e->getMessage()
    ]);
    exit;
}
