<?php 
include_once('header.php');
include_once '../controller/conexion.php';

if (!isset($_GET['id'])) {
    die('ID de proyecto no proporcionado.');
}

$id_proyecto = intval($_GET['id']);

try {
    $stmt = $connect->prepare("SELECT * FROM img_proyectos WHERE fk_proyecto = :id");
    $stmt->bindParam(':id', $id_proyecto, PDO::PARAM_INT);
    $stmt->execute();
    $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener imágenes: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imágenes del Proyecto</title>
    <style>
        .contenedor {
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .grid img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .volver {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .volver:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="con_volver">
        <a href="lista_proyectos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Proyectos</h3>
    </div>

    <div class="contenedor">
        <?php if (count($imagenes) > 0): ?>
            <div class="grid">
                <?php foreach ($imagenes as $img): ?>
                    <img src="../uploads/<?= htmlspecialchars($img['img']) ?>" alt="Imagen del proyecto">
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay imágenes para este proyecto.</p>
        <?php endif; ?>
    </div>
</body>
</html>
