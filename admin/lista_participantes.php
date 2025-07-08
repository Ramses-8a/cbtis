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

<div class="con_volver">
        <a href="lista_torneos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3></h3>
    </div>

<div class="container">
    <h1>Lista de Participantes</h1>
    
    <div class="table-container">
        <table class="participants-table">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Nombre</th>
                    <th>Grado</th>
                    <th>Grupo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($alumnos)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px; background-color: #f9f9f9; color: #555;">
                            <strong>No hay participantes registrados actualmente.</strong><br>
                            Cuando se inscriban participantes, aparecerán aquí.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $id = 1; ?>
                    <?php foreach ($alumnos as $alumno): ?>
                        <tr>
                            <!-- <td><?= $id ?></td> -->
                            <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                            <td><?= htmlspecialchars($alumno['grado']) ?></td>
                            <td><?= htmlspecialchars($alumno['grupo']) ?></td>
                        </tr>
                        <?php $id++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
