<?php
require_once 'controller/conexion.php';
require_once 'header.php';

if (!isset($_GET['pk_curso'])) {
    exit("Curso no especificado.");
}

$pk_curso = $_GET['pk_curso'];

$stmt = $connect->prepare("SELECT c.*, tr.nom_tipo, l.nom_lenguaje FROM cursos c JOIN tipo_cursos tr ON c.fk_tipo_curso = tr.pk_tipo_curso JOIN lenguajes l ON c.fk_lenguaje = l.pk_lenguaje WHERE c.pk_curso = ?");
$stmt->execute([$pk_curso]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    exit("cursono encontrado.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ver_proyecto.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title><?= $curso['nom_curso'] ?></title>
</head>

<body>
    <div class="con_volver">
        <a href="mostrar_cursos.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3><?=$curso['nom_curso']?></h3>
    </div>

    <main>
        <div>
            <?php if (!empty($curso['img'])): ?>
                <img src="uploads/<?= $curso['img'] ?>" alt="Custom Image">
            <?php else: ?>
                <?php
                $youtube_link = $curso['link'];
                $video_id = '';

                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $youtube_link, $matches)) {
                    $video_id = $matches[1];
                }
                ?>
                <?php if (!empty($video_id)): ?>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?= $video_id ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <?php else: ?>
                    <p>No hay video disponible.</p>
                <?php endif; ?>
            <?php endif; ?>

            <p><strong>Nombre:</strong> <?= $curso['nom_curso'] ?></p>
            <p><strong>Descripción:</strong> <?= $curso['descripcion'] ?></p>
            <p><strong>Tipo de Curso:</strong> <?= $curso['nom_tipo'] ?></p>
            <p><strong>Lenguaje:</strong> <?= $curso['nom_lenguaje'] ?></p>
            <p><strong>Enlace:</strong> <a href="<?= $curso['link'] ?>" target="_blank"><?= $curso['link'] ?></a></p>
        </div>
    </main>
</body>

</html>