<?php
header('Content-Type: application/json');
require_once('../conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se recibieron los datos necesarios
    if (isset($_POST['id']) && isset($_POST['nom_tipo'])) {
        $id = $_POST['id'];
        $nom_tipo = trim($_POST['nom_tipo']);
        
        // Validar que el nombre no esté vacío
        if (empty($nom_tipo)) {
            echo json_encode([
                'success' => false,
                'status' => 'error',
                'message' => 'El nombre del tipo de curso no puede estar vacío'
            ]);
            exit;
        }
        
        try {
            // Verificar si ya existe otro tipo de curso con el mismo nombre (excluyendo el actual)
            $stmt_check = $connect->prepare("SELECT COUNT(*) FROM tipo_cursos WHERE nom_tipo = ? AND pk_tipo_curso != ?");
            $stmt_check->execute([$nom_tipo, $id]);
            $count = $stmt_check->fetchColumn();
            
            if ($count > 0) {
                echo json_encode([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Ya existe un tipo de curso con ese nombre'
                ]);
                exit;
            }
            
            // Actualizar el tipo de curso
            $stmt = $connect->prepare("UPDATE tipo_cursos SET nom_tipo = ? WHERE pk_tipo_curso = ?");
            $result = $stmt->execute([$nom_tipo, $id]);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'status' => 'success',
                    'message' => 'Tipo de curso actualizado correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Error al actualizar el tipo de curso'
                ]);
            }
            
        } catch (PDOException $e) {
            // Log del error para debugging
            error_log('Error PDO en actualizar_tipo.php: ' . $e->getMessage());
            
            echo json_encode([
                'success' => false,
                'status' => 'error',
                'message' => 'Error en la base de datos: ' . $e->getMessage(),
                'error_code' => $e->getCode(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        } catch (Exception $e) {
            // Capturar cualquier otro tipo de error
            error_log('Error general en actualizar_tipo.php: ' . $e->getMessage());
            
            echo json_encode([
                'success' => false,
                'status' => 'error',
                'message' => 'Error inesperado: ' . $e->getMessage(),
                'error_code' => $e->getCode(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
        
    } else {
        echo json_encode([
            'success' => false,
            'status' => 'error',
            'message' => 'Datos incompletos'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
}
?>