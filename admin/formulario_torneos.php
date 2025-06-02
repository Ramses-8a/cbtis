<?php 
include_once('header.php');
?>
<head>
     <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Agregar Torneos</title>
</head>

<form id="formProyecto" action="guardar_torneo.php" method="POST" enctype="multipart/form-data">

    <div>
        <label for="nom_torneo">Nombre del Torneo:</label>
        <input type="text" id="nom_torneo" name="nom_torneo" >
    </div>
    <div>
        <label for="tipo_torneo">Tipo de torneo:</label>
            <select name="fk_tipo_torneo" required>
                <?php
                    include_once '../controller/conexion.php';
                    $stmt = $connect->prepare("SELECT pk_tipo_torneo, nom_tipo FROM tipo_torneos WHERE estatus = 1");
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) {
                    echo "<option value='{$row['pk_tipo_torneo']}'>{$row['nom_tipo']}</option>";
                    }
                ?>
            </select>
        
    </div>

    <div>
        <label for="img_proyecto">Imagen del torneo:</label>
        <input type="file" id="img_proyecto" name="img_proyecto" accept="image/*" >
        <div id="preview_principal" style="max-width: 200px; max-height: 200px; margin-top: 10px;"></div>
    </div>

    <div>
        <label for="descripcion">Descripciónn:</label>
        <input type="text" id="descripcion" name="descripcion" >
    </div>

    <div>
        <label for="detalles">Detalles del Torneo:</label>
        <input type="text" id="detalles" name="detalles">
    </div>

    <div>
        <label>Imágenes Adicionales:</label>
        <div>
            <input type="file" id="img_adicionales" name="img_adicionales[]" accept="image/*" multiple>
            <span id="contador_imagenes">0/10 imágenes seleccionadas</span>
        </div>
        <div id="preview_adicionales" style="margin-top: 10px;"></div>
    </div>

    <div>
        <button type="submit">Crear Torneo</button>
    </div>
</form>

