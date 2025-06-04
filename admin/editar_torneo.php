<?php
include('../controller/torneo/buscar_torneo.php');
include_once('header.php'); 

if (!isset($_GET['pk_torneo'])) {
    header('Location: lista_torneos.php');
    exit;
}

?>

<form action="../controller/torneo/actualizar_torneo.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="pk_torneo" value="<?php echo $pk_torneo; ?>">
    
    <div>
        <label for="nom_torneo">Nombre del Torneo:</label>
        <input type="text" id="nom_torneo" name="nom_torneo" value="<?php echo $torneo['nom_torneo']; ?>" required>
    </div>

    <div>
        <label for="fk_tipo_torneo">Tipo de Torneo:</label>
        <input type="number" id="fk_tipo_torneo" name="fk_tipo_torneo" value="<?php echo $torneo['fk_tipo_torneo']; ?>" required>
    </div>

    <div>
        <label for="estatus">Estatus:</label>
        <input type="number" id="estatus" name="estatus" value="<?php echo $torneo['estatus']; ?>" required>
    </div>

    <div>
        <label for="img">Imagen:</label>
        <input type="file" id="img" name="img">
        <?php if(!empty($torneo['img'])): ?>
            <img src="../img/<?php echo $torneo['img']; ?>" width="100">
            <input type="hidden" name="current_img" value="<?php echo $torneo['img']; ?>">
        <?php endif; ?>
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $torneo['descripcion']; ?></textarea>
    </div>

    <div>
        <label for="detalles">Detalles:</label>
        <textarea id="detalles" name="detalles" required><?php echo $torneo['detalles']; ?></textarea>
    </div>

    <button type="submit">Actualizar Torneo</button>
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
        const data = JSON.parse(text); // Intentamos parsear manualmente
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message
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