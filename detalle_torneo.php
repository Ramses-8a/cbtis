<?php
require_once(__DIR__ . '../controller/conexion.php');
require_once 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de torneo no válido.";
    exit;
}

$id = (int) $_GET['id'];

$sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo
        FROM torneos t
        INNER JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo
        WHERE t.pk_torneo = ? AND t.estatus = 1";

$stmt = $connect->prepare($sql);
$stmt->execute([$id]);
$torneo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$torneo) {
    echo "Torneo no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalle del Torneo - <?= htmlspecialchars($torneo['nom_torneo']) ?></title>
    <link rel="stylesheet" href="css/detalle_torneo.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

</head>
<body>
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
   <div class="con_volver">
        <a href="torneos_activos.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3>Torneos</h3>
    </div>

    <h1><?= htmlspecialchars($torneo['nom_torneo']) ?></h1>
    <p><strong>Tipo:</strong> <?= htmlspecialchars($torneo['nom_tipo']) ?></p>
    <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($torneo['descripcion'])) ?></p>
    <p><strong>Detalles:</strong> <?= nl2br(htmlspecialchars($torneo['detalles'])) ?></p>

    <?php if (!empty($torneo['img'])): ?>
        <p><strong>Imagen Principal:</strong><br>
        <img src="../../uploads/<?= urlencode($torneo['img']) ?>" alt="Imagen principal"></p>
    <?php endif; ?>

    <div class="imagenes-adicionales">
        <strong>Imágenes adicionales:</strong><br>
        <?php
        $sql_imgs = "SELECT img FROM img_torneos WHERE fk_torneo = ?";
        $stmt_imgs = $connect->prepare($sql_imgs);
        $stmt_imgs->execute([$torneo['pk_torneo']]);
        $imgs = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);

        if (count($imgs) === 0) {
            echo "<p>No hay imágenes adicionales.</p>";
        } else {
            foreach ($imgs as $img):
        ?>
                <img src="../../uploads/<?= ($img['img']) ?>" alt="Imagen adicional">
        <?php
            endforeach;
        }
        ?>
    </div>

<!-- este boton es para abril el modal -->
<button class="btn-flotante" id="btnAbrirModal">+</button>

<!-- este es el modal para incribirse -->
<div class="modal" id="modalInscripcion">
  <div class="modal-contenido">
    <span class="cerrar" id="cerrarModal">&times;</span>
    <h2>¡Inscríbete al torneo!</h2>
    <form>
      <input type="text" value="<?=$torneo['pk_torneo']?>">
      <label for="nombre">Nombre completo:</label>
      <input type="text" id="nombre" name="nombre" required>

      <label for="grado">Grado:</label>
      <input type="text" id="grado" name="grado" required>

      <label for="grupo">Grupo:</label>
      <input type="text" id="grupo" name="grupo" required>

      <button type="submit" class="btn-inscribirse">Inscribirse</button>
    </form>
  </div>
</div>


</body>
</html>

<script>
    document.getElementById("btnAbrirModal").onclick = function () {
        document.getElementById("modalInscripcion").style.display = "flex";
    };

    document.getElementById("cerrarModal").onclick = function () {
        document.getElementById("modalInscripcion").style.display = "none";
    };

    window.onclick = function (event) {
        const modal = document.getElementById("modalInscripcion");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>



