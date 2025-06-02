<?php
require_once(__DIR__ . '/../conexion.php');

try {
    $pk_proyecto = $_POST['pk_proyecto'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (isset($_FILES['img_adicionales']) && !empty($_FILES['img_adicionales']['name'][0])) {
        $total_adicionales = count($_FILES['img_adicionales']['name']);

        if ($total_adicionales > 10) {
            throw new Exception("No se pueden subir más de 10 imágenes adicionales");
        }

        for ($i = 0; $i < $total_adicionales; $i++) {
            if (!in_array($_FILES['img_adicionales']['type'][$i], $allowed_types)) {
                throw new Exception("Tipo de archivo no permitido en imagen adicional " . ($i + 1));
            }

            if ($_FILES['img_adicionales']['size'][$i] > $max_size) {
                throw new Exception("Imagen adicional " . ($i + 1) . " excede el tamaño máximo de 5MB");
            }

            $img_adicional_nombre = uniqid() . "_" . basename($_FILES['img_adicionales']['name'][$i]);
            $img_adicional_ruta = "../../img/" . $img_adicional_nombre;

            if (!move_uploaded_file($_FILES['img_adicionales']['tmp_name'][$i], $img_adicional_ruta)) {
                throw new Exception("Error al guardar imagen adicional " . ($i + 1));
            }

            $stmt_img = $connect->prepare("INSERT INTO img_proyectos (img, fk_proyecto) VALUES (:img, :fk_proyecto)");
            $stmt_img->bindParam(':img', $img_adicional_nombre);
            $stmt_img->bindParam(':fk_proyecto', $pk_proyecto);
            $stmt_img->execute();
        }

        echo json_encode(['status' => 'success', 'message' => 'Imágenes subidas correctamente']);
        exit;
    } else {
        throw new Exception("No se enviaron imágenes");
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
