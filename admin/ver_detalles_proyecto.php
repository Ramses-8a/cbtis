<?php
include_once('header.php');
include('../controller/proyecto/buscar_proyecto.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
     <link rel="stylesheet" href="../css/ver_proyecto.css">
    <title><?= $proyecto['nom_proyecto'] ?></title>
</head>
<body>

    <div class="con_volver">
        <a href="mostrar_proyectos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3><?= $proyecto['nom_proyecto'] ?></h3>
    </div>

    <main class="detalle-proyecto">
        
        
        <div class="proyecto-container">
            <h3 class="img_relacionadas">Imágenes relacionadas</h3>
            <div class="galeria-imagenes">
                <?php 
                $primera_imagen = true;
                foreach ($imagenes as $img): ?>
                    <img src="../img/<?= $img['img'] ?>" 
                         alt="Imagen del proyecto" 
                         class="imagen-proyecto <?= $primera_imagen ? 'imagen-destacada' : '' ?>">
                    <?php $primera_imagen = false; ?>
                <?php endforeach; ?>
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

<style>
    /* contenedor para volver */
.con_volver {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px 20px;
  background-color: white;
}

.con_volver .volver img {
  width: 28px;
  height: 28px;
  object-fit: contain;
  cursor: pointer;
}

.con_volver h3 {
  font-size: 2.5rem;
  font-weight: bold;
  margin: 0;
}
</style>