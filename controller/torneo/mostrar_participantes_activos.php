<?php
require_once(__DIR__ . '/../conexion.php');

try {
    // Obtener torneos activos
    $stmt_torneos = $connect->prepare("SELECT pk_torneo FROM torneos WHERE estatus = 1");
    $stmt_torneos->execute();
    $torneos_activos = $stmt_torneos->fetchAll(PDO::FETCH_COLUMN);

    $participantes_activos_count = 0;
    if (!empty($torneos_activos)) {
        // Convertir array de IDs de torneos a string para la cláusula IN
        $placeholders = implode(',', array_fill(0, count($torneos_activos), '?'));
        
        // Contar participantes únicos en torneos activos
        $stmt_participantes = $connect->prepare("SELECT COUNT(DISTINCT pk_alumno_torneo) FROM alumno_torneos WHERE fk_torneo IN ($placeholders)");
        $stmt_participantes->execute($torneos_activos);
        $participantes_activos_count = $stmt_participantes->fetchColumn();
    }
    
    return $participantes_activos_count;

} catch (PDOException $e) {
    // En caso de error, retornar 0 o manejar el error según sea necesario
    // Para este caso, retornaremos 0 para no romper la visualización en el dashboard
    error_log("Error al obtener participantes activos: " . $e->getMessage());
    return 0;
}

?>