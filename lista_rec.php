<?php
require_once 'controller/recursos/mostrar_recursos.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($recurso['nom_recurso']) ?> - Detalle</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #1f2c3a, #2d3d50);
            margin: 0;
            padding: 0;
            color: #2c3e50;
        }

        .container {
            max-width: 700px;
            margin: 80px auto 40px; 
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }

        .container h1 {
            color: #c40000;
            font-size: 26px;
            margin-bottom: 20px;
        }

        .recurso-img img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .detalle {
            font-size: 16px;
            margin-bottom: 12px;
        }

        .detalle span {
            font-weight: 600;
            color: #2c3e50;
        }

        .boton {
            display: inline-block;
            margin-top: 25px;
            background-color: #b60000;
            color: white;
            padding: 10px 22px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .boton:hover {
            background-color: #930000;
        }

        .volver {
            background-color: #7f8c8d;
        }

        .volver:hover {
            background-color: #5d6d6f;
        }
    </style>
</head>
<body>

<?php include_once 'header.php'; ?>

<div class="container">
    <h1>Imágenes relacionadas</h1>

    <div class="recurso-img">
        <?php if (!empty($recurso['img'])): ?>
            <img src="uploads/<?= htmlspecialchars($recurso['img']) ?>" alt="Imagen de <?= htmlspecialchars($recurso['nom_recurso']) ?>">
        <?php else: ?>
            <p>Sin imagen disponible</p>
        <?php endif; ?>
    </div>

    <div class="detalle"><span>Nombre:</span> <?= htmlspecialchars($recurso['nom_recurso']) ?></div>
    <div class="detalle"><span>Descripción:</span> <?= nl2br(htmlspecialchars($recurso['descripcion'])) ?></div>
    <div class="detalle"><span>Tipo:</span> <?= htmlspecialchars($recurso['nom_tipo']) ?></div>

    <?php if (!empty($recurso['url'])): ?>
        <a href="<?= htmlspecialchars($recurso['url']) ?>" class="boton" target="_blank" rel="noopener noreferrer">Visitar</a>
    <?php endif; ?>

    <br><br>
    <a href="mostrar_recursos.php" class="boton volver">Volver</a>
</div>

</body>
</html>
