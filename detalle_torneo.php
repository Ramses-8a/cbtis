<?php
require_once 'controller/conexion.php';
require_once 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de torneo no válido.";
    exit;
}

$id = (int) $_GET['id'];

$sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo, t.finicio, t.ffinal
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <div class="con_volver">
        <a href="torneos_activos.php" class="volver">
            <img src="img/volver.webp" alt="Volver">
        </a>
        <h3>Torneos</h3>
    </div>
    
<div class="torneo-detalle">
    <h1><?= htmlspecialchars($torneo['nom_torneo']) ?></h1>

    <div class="torneo-detalle-flex">
        <?php if (!empty($torneo['img'])): ?>
            <div class="torneo-img">
                <img src="uploads/<?= htmlspecialchars($torneo['img']) ?>" alt="Imagen del torneo">
            </div>
        <?php endif; ?>

        <div class="torneo-info">
            <p><strong>Tipo:</strong> <?= htmlspecialchars($torneo['nom_tipo']) ?></p>
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($torneo['descripcion'])) ?></p>
            <p><strong>Detalles:</strong> <?= nl2br(htmlspecialchars($torneo['detalles'])) ?></p>
            <p><strong>Fecha de inicio de inscripciones:</strong> <?= date('d/m/Y', strtotime($torneo['finicio'])) ?></p>
            <p><strong>Fecha límite de inscripciones:</strong> <?= date('d/m/Y', strtotime($torneo['ffinal'])) ?></p>
        </div>
    </div>
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
          <!-- <input type="text" id="grado" name="grado" required> -->
           <select name="grado" id="grado" required>
                <option value="" selected>Selecciona una opción</option>
                <option value="Segundo">2°do</option>
                <option value="Tercero">3°ro</option>
                <option value="Cuarto">4°to</option>
                <option value="Quinto">5°to</option>
                <option value="Sexto">6°to</option>
           </select>

          <label for="grupo">Grupo:</label>
            <select name="grupo" id="grupo" required>
                <option value="" selected>Selecciona una opción</option>
                <option value="B">B</option>
                <option value="C">C</option>
           </select>

          <!-- reCAPTCHA -->
          <div class="g-recaptcha" data-sitekey="6Le-_fsqAAAAABPT_xPcyqAfc9TCmAbh52c2Q_M0"></div>

          <button type="submit" class="btn-inscribirse">Inscribirse</button>
        </form>
        <div id="mensajeInscripcion" style="margin-top:10px; font-weight:600;"></div>
      </div>
    </div>

<script>
    document.getElementById("btnAbrirModal").onclick = function () {
        document.getElementById("modalInscripcion").style.display = "flex";
    };

    document.getElementById("cerrarModal").onclick = function () {
        document.getElementById("modalInscripcion").style.display = "none";
        document.getElementById('mensajeInscripcion').textContent = '';
    };

    window.onclick = function (event) {
        const modal = document.getElementById("modalInscripcion");
        if (event.target === modal) {
            modal.style.display = "none";
            document.getElementById('mensajeInscripcion').textContent = '';
        }
    };

    document.getElementById('formInscripcion').addEventListener('submit', function(e) {
        e.preventDefault();

        const fechaActual = new Date();
        const fechaInicio = new Date('<?php echo $torneo["finicio"]; ?>');
        const fechaFinal = new Date('<?php echo $torneo["ffinal"]; ?>');

        if (fechaActual < fechaInicio) {
            Swal.fire({
                icon: 'warning',
                title: 'Inscripciones no disponibles',
                text: 'Las inscripciones para este torneo aún no están abiertas. Estarán disponibles a partir del ' + fechaInicio.toLocaleDateString(),
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        if (fechaActual > fechaFinal) {
            Swal.fire({
                icon: 'error',
                title: 'Inscripciones cerradas',
                text: 'El período de inscripción para este torneo ha finalizado.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        const form = e.target;
        const data = new FormData(form);

        fetch('guardar_inscripcion.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Inscripción exitosa!',
                    text: res.message,
                    confirmButtonText: 'Aceptar',
                    customClass: { popup: 'swal2-popup' }
                }).then(() => {
                    document.getElementById('modalInscripcion').style.display = 'none';
                });
                form.reset();
            } else {
                const mensajeDiv = document.getElementById('mensajeInscripcion');
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

<style>
    /* estilo del contenedor para volver */
.con_volver {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px 20px;
  background-color: white;
}

.con_volver .volver img {
  width: 30px;
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