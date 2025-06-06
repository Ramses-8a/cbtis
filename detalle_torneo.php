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


<style>
    body {
        font-family: "Segoe UI", sans-serif;
        background: #fff;
        color: #333;
        padding: 0;
    }

    h1 {
        color: #dc3545;
        margin-bottom: 20px;
        text-align: center;
        font-family: 'Poppins', 'Segoe UI', sans-serif;
    }

    p {
        line-height: 1.5;
        text-align: center;
    }

    img {
        max-width: 300px;
        margin: 10px auto;
        display: block;
        border-radius: 10px;
    }

    .imagenes-adicionales {
        text-align: center;
        margin-top: 20px;
    }

    .imagenes-adicionales img {
        max-width: 120px;
        margin: 10px 5px;
        border-radius: 8px;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
    }

    /* boton flotante circular */
    .btn-flotante {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #9d0707;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 32px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
        z-index: 1001;
    }

    .btn-flotante:hover {
        background-color: darkred;
    }

    /* estilo del modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0,0,0,0.6);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-contenido {
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        position: relative;
        text-align: left;
        animation: popIn 0.3s ease;
    }

    @keyframes popIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-contenido h2 {
        margin-top: 0;
        color: #dc3545;
        text-align: center;
         font-family: 'Poppins', 'Segoe UI', sans-serif;

    }

    .modal-contenido label {
        display: block;
        margin: 15px 0 5px;
        font-weight: bold;
        font-size: 14px;
    }

    .modal-contenido input {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        outline: none;
    }

    .btn-inscribirse {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #9d0707;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-inscribirse:hover {
        background-color: darkred;
    }

    .cerrar {
        position: absolute;
        top: 12px;
        right: 16px;
        font-size: 28px;
        color: #888;
        cursor: pointer;
    }

    .cerrar:hover {
        color: #000;
    }
</style>
