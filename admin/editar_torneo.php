<?php
include('../controller/torneo/buscar_torneo.php');
include_once('header.php'); 

if (!isset($_GET['pk_torneo'])) {
    header('Location: lista_torneos.php');
    exit;
}

?>
<head>
    <link rel="stylesheet" href="../css/form_editar.css">
</head>

<div class="con_volver">
        <a href="lista_proyectos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Torneos</h3>
        </div>

<form action="../controller/torneo/actualizar_torneo.php" method="POST" enctype="multipart/form-data" class="form-editar">
    <input type="hidden" name="pk_torneo" value="<?php echo $pk_torneo; ?>">
    
    <div class="nom-proyecto">
        <label for="nom_torneo">Nombre del Torneo:</label>
        <input type="text" id="nom_torneo" name="nom_torneo" value="<?php echo $torneo['nom_torneo']; ?>" required>
    </div>

    <div class="nom-proyecto">
        <label for="fk_tipo_torneo">Tipo de Torneo:</label>
        <select class="" name="fk_tipo_torneo" id="fk_tipo_torneo" required>
            <?php
            require_once(__DIR__ . '/../controller/torneo/mostrar_tipo_torneos.php');
            $tipos_torneo = getTiposTorneo($connect); // Assuming a function `getTiposTorneo` is created

            foreach ($tipos_torneo as $tipo) {
                $selected = ($tipo['pk_tipo_torneo'] == $torneo['fk_tipo_torneo']) ? 'selected' : '';
                echo "<option value=\"{$tipo['pk_tipo_torneo']}\" {$selected}>{$tipo['nom_tipo']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="img-proyecto">
        <label for="img">Imagen:</label>
        <input type="file" id="img" name="img">
        <?php if(!empty($torneo['img'])): ?>
            <img src="../uploads/<?php echo $torneo['img']; ?>" width="100">
            <input type="hidden" name="current_img" value="<?php echo $torneo['img']; ?>">
        <?php endif; ?>
    </div>

    <div class="desc-proyecto">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $torneo['descripcion']; ?></textarea>
    </div>

    <div class="detalles-proyecto">
        <label for="detalles">Detalles:</label>
        <textarea id="detalles" name="detalles" required><?php echo $torneo['detalles']; ?></textarea>
    </div>

    <div>
        <label for="finicio">Fecha de inicio:</label>
        <input type="date" name="finicio" id="finicio" value="<?php echo $torneo['finicio']; ?>" required>

        <label for="ffinal">Fecha limite:</label>
        <input type="date" name="ffinal" id="ffinal" value="<?php echo $torneo['ffinal']; ?>" required>
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Actualizar Torneo</button>
        <button class="cancelar" type="submit" onclick="window.location.href='lista_torneos.php'">Cancelar</button>
    </div>
</form>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this); // Get form data

        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
.then(text => {
    console.log(text); // 👈 Aquí verás el HTML o el error real del servidor

    try {
        const data = JSON.parse(text);
        console.log(data); // Añadido para depuración
        // Intentamos parsear manualmente
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message
            }).then(() => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            });
        } else if (data.status === 'warning') {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: data.message
            }).then(() => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    } catch (e) {
        // Si no se pudo parsear, mostramos el error en crudo
        Swal.fire({
            icon: 'error',
            title: 'Error crítico',
            html: `<pre>${text}</pre>`
        });
    }
})


    });
</script>

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
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
}
</style>