<?php
include('../controller/torneo/buscar_participantes.php');
include_once('header.php');

// The $alumnos variable should now be available from buscar_participantes.php if pk_torneo was set

?>

<div class="container">
    <h1>Lista de Participantes</h1>


            <?php foreach ($alumnos as $alumno): ?>
               <h1><?=$alumno['nombre']?></h1>
               <h1><?=$alumno['grupo']?></h1>
               <h1><?=$alumno['grado']?></h1>
            <?php endforeach; ?>

</div>
