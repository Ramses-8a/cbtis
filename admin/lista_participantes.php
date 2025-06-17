<?php
include('../controller/torneo/buscar_participantes.php');
include_once('header.php');

// The $alumnos variable should now be available from buscar_participantes.php if pk_torneo was set

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Participantes del Torneo</title>
    <link rel="stylesheet" href="../css/lista_participantes.css">
</head>

<div class="container">
    <h1>Lista de Participantes</h1>

    <?php foreach ($alumnos as $alumno): ?>
            <div class="participant-card">
                <h2><?= htmlspecialchars($alumno['nombre']) ?></h2>
                <p><strong>Grupo:</strong> <?= htmlspecialchars($alumno['grupo']) ?></p>
                <p><strong>Grado:</strong> <?= htmlspecialchars($alumno['grado']) ?></p>
            </div>
    <?php endforeach; ?>
</div>
