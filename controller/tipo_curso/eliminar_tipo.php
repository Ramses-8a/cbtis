<?php
require_once('../conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        
        if (empty($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID del tipo de curso es requerido'
            ]);
            exit;
        }
        
        // Eliminar el tipo de curso de la base de datos
        $sql = "DELETE FROM tipo_cursos WHERE pk_tipo_curso = :id";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tipo de curso eliminado correctamente'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontró el tipo de curso a eliminar'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al eliminar el tipo de curso'
            ]);
        }
        
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
}
?>