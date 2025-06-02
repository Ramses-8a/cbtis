<?php
include('../controller/torneos/mostrar_proyecto.php');
include_once('header.php'); 

// Obtener el ID del torneo de la URL
$pk_torneo = isset($_GET['pk_torneo']) ? $_GET['pk_torneo'] : null;

// Verificar si se recibió un ID válido
if (!$pk_torneo) {
    echo "Error: No se especificó un torneo para editar";
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