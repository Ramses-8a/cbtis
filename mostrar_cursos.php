<?php

    include('controller/cursos/mostrar_cursos.php');
    include_once('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/proyecto.css">
    <title>Cursos</title>
</head>
<body>
    <div class="con_volver">
        <a href="index.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3>Cursos</h3>

    </div>

    <main class="proyectos">
    <?php foreach ($cursos as $curso):
            // Add this condition to check the status
            if (isset($curso['estatus']) && $curso['estatus'] == 1):
        ?>
            <a href="ver_curso.php?pk_curso=<?= $curso['pk_curso'] ?>" class="card">
                <?php if (!empty($curso['img'])): ?>
                    <img src="uploads/<?= $curso['img'] ?>" alt="Custom Image" style="width: 100%; height: auto;">
                <?php else: ?>
                    <?php
                    $youtube_link = $curso['link'];
                    $video_id = '';
                    $thumbnail_url = '';

                    // Extract video ID from various YouTube URL formats
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $youtube_link, $matches)) {
                        $video_id = $matches[1];
                        $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
                    }
                    ?>
                    <?php if (!empty($thumbnail_url)): ?>
                        <img src="<?= $thumbnail_url ?>" alt="YouTube Thumbnail" style="width: 100%; height: auto;">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                <?php endif; ?>
                <p><strong><?= $curso['nom_curso'] ?></strong></p>
                <p><?= $curso['descripcion'] ?></p>
            </a>
            <?php 
            endif; // Close the if condition
            endforeach; 
        ?>
    </main>
</body>
</html>
