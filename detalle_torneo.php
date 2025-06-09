<?php
require_once 'controller/conexion.php';
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
        <img src="../../uploads/<?= htmlspecialchars($torneo['img']) ?>" alt="Imagen principal" style="max-width:100%; height:auto;"></p>
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
                <img src="../../uploads/<?= htmlspecialchars($img['img']) ?>" alt="Imagen adicional" style="max-width:150px; margin:5px;">
        <?php
            endforeach;
        }
        ?>
    </div>

    <!-- Botón para abrir modal -->
    <div class="btn-flotante" id="btnAbrirModal">+</div>

    <!-- Modal inscripción -->
    <div class="modal" id="modalInscripcion" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); justify-content:center; align-items:center;">
      <div class="modal-contenido" style="background-color:#fff; padding:20px; border-radius:8px; width:90%; max-width:400px; position:relative;">
        <span class="cerrar" id="cerrarModal" style="position:absolute; top:10px; right:15px; font-size:28px; font-weight:bold; color:#333; cursor:pointer;">&times;</span>
        <h2>¡Inscríbete al torneo!</h2>
        <form id="formInscripcion">
          <input type="hidden" name="pk_torneo" value="<?= htmlspecialchars($torneo['pk_torneo']) ?>">
          <label for="nombre">Nombre completo:</label>
          <input type="text" id="nombre" name="nombre" required>

          <label for="grado">Grado:</label>
          <input type="text" id="grado" name="grado" required>

          <label for="grupo">Grupo:</label>
          <input type="text" id="grupo" name="grupo" required>

          <button type="submit" class="btn-inscribirse">Inscribirse</button>
        </form>
        <div id="mensajeInscripcion" style="margin-top:10px; font-weight:600;"></div>
      </div>
    </div>

<script>
    // Abrir modal
    document.getElementById("btnAbrirModal").onclick = function () {
        document.getElementById("modalInscripcion").style.display = "flex";
    };

    // Cerrar modal con la X
    document.getElementById("cerrarModal").onclick = function () {
        document.getElementById("modalInscripcion").style.display = "none";
        document.getElementById('mensajeInscripcion').textContent = '';
    };

    // Cerrar modal al hacer clic fuera
    window.onclick = function (event) {
        const modal = document.getElementById("modalInscripcion");
        if (event.target === modal) {
            modal.style.display = "none";
            document.getElementById('mensajeInscripcion').textContent = '';
        }
    };

    // Enviar formulario con AJAX
    document.getElementById('formInscripcion').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const data = new FormData(form);

        fetch('guardar_inscripcion.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(res => {
            const mensajeDiv = document.getElementById('mensajeInscripcion');
            if (res.success) {
                mensajeDiv.style.color = 'green';
                mensajeDiv.textContent = res.message;
                form.reset();
                setTimeout(() => {
                    document.getElementById('modalInscripcion').style.display = 'none';
                    mensajeDiv.textContent = '';
                }, 2000);
            } else {
                mensajeDiv.style.color = 'red';
                mensajeDiv.textContent = res.message;
            }
        })
        .catch(() => {
            const mensajeDiv = document.getElementById('mensajeInscripcion');
            mensajeDiv.style.color = 'red';
            mensajeDiv.textContent = 'Error de conexión.';
        });
    });
</script>

</body>
</html>
