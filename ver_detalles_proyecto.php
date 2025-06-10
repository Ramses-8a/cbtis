<?php
require_once 'controller/conexion.php';
require_once 'header.php';

if (!isset($_GET['pk_proyecto'])) {
    exit("Proyecto no especificado.");
}

$pk_proyecto = $_GET['pk_proyecto'];

$stmt = $connect->prepare("SELECT * FROM proyectos WHERE pk_proyecto = ?");
$stmt->execute([$pk_proyecto]);
$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proyecto) {
    exit("Proyecto no encontrado.");
}

$stmt_imgs = $connect->prepare("SELECT img FROM img_proyectos WHERE fk_proyecto = ?");
$stmt_imgs->execute([$pk_proyecto]);
$imagenes = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/ver_proyecto.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title><?= $proyecto['nom_proyecto'] ?></title>
</head>
<body>
    <div class="con_volver">
        <a href="mostrar_proyectos.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3><?=$proyecto['nom_proyecto']?></h3>
    </div>

    <main class="detalle-proyecto">
        <div class="proyecto-container">
            <h3 class="img_relacionadas">Imágenes relacionadas</h3>
            <div class="galeria-imagenes">
                <?php 
                if (empty($imagenes)) {
                    echo "<p>No hay imágenes adicionales para este proyecto.</p>";
                } else {
                    $primera_imagen = true;
                    foreach ($imagenes as $img): ?>
                        <img src="img/<?= $img['img'] ?>" 
                             alt="Imagen del proyecto" 
                             class="imagen-proyecto <?= $primera_imagen ? 'imagen-destacada' : '' ?>">
                        <?php $primera_imagen = false; ?>
                    <?php endforeach;
                }
                ?>
            </div>

            <div class="contenido-proyecto">
                <p class="descripcion-proyecto">
                    <?= $proyecto['descripcion'] ?>
                </p>
                <a href="<?php echo $proyecto['url']; ?>" class="boton">Visitar</a>
            </div>
        </div>
    </main>
</body>
</html>