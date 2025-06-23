<?php
    include('controller/proyecto/mostrar_proyecto.php');
    include_once('header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/proyecto.css">
    <title>Proyectos</title>
</head>
<body>

    <div class="con_volver">
        <a href="index.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3>Proyectos</h3>
    </div>
       <!-- Contenedor del buscador alineado a la derecha -->
<div class="contenedor-buscador">
  <input type="text" id="buscador-proyectos" placeholder="Buscar proyecto.." class="buscador">
</div>

<main class="proyectos">
    <?php foreach ($proyectos as $proyecto):
    if (isset($proyecto['estatus']) && $proyecto['estatus'] == 1): ?>
        <a href="ver_detalles_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>" class="card">
            <img src="uploads/<?= $proyecto['img_proyecto'] ?>" alt="Proyecto">
            <p><strong class="nombre-proyecto"><?= $proyecto['nom_proyecto'] ?></strong></p>
        </a>
    <?php endif;
    endforeach; ?>
</main>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscador-proyectos');
    const proyectos = document.querySelectorAll('.proyectos .card');
    
    buscador.addEventListener('input', function() {
        const textoBusqueda = this.value.trim().toLowerCase();
        
        proyectos.forEach(proyecto => {
            const nombreProyecto = proyecto.querySelector('.nombre-proyecto').textContent.toLowerCase();
            
            // Mostrar u ocultar según coincida con la búsqueda
            proyecto.style.display = nombreProyecto.includes(textoBusqueda) ? 'block' : 'none';
        });
    });
});
</script>

</body>
</html>



