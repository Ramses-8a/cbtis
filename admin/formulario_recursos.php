<?php
include_once('header.php');
?>
<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Subir recursos</title>
</head>
<div class="con_volver">
            <a href="index.php" class="volver">
                <img src="../img/volver.webp" alt="Volver">
            </a>
            <h3>Recursos</h3>
</div>
<form id="formRecurso" enctype="multipart/form-data" class="form-proyectos">
    <div>
        <label for="nom_recurso">Nombre del recurso:</label>
        <input type="text" id="nom_recurso" name="nom_recurso" required placeholder="Escribe el nombre del recurso">
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required placeholder="Escribe la descripción del recurso"></textarea>
    </div>

    <div>
        <label for="pk_tipo_recurso">Tipo de recurso:</label>
        <select name="pk_tipo_recurso" id="pk_tipo_recurso" required>
            <?php
            include_once '../controller/conexion.php';
            
            // Función para obtener tipos de recurso directamente
            try {
                $stmt = $connect->prepare("SELECT pk_tipo_recurso, nom_tipo FROM tipo_recursos WHERE estatus = 1 ORDER BY nom_tipo ASC");
                $stmt->execute();
                $tipos_recurso = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($tipos_recurso as $row) {
                    echo "<option value='{$row['pk_tipo_recurso']}'>{$row['nom_tipo']}</option>";
                }
            } catch (PDOException $e) {
                echo "<option value=''>Error al cargar tipos de recurso</option>";
            }
            ?>
        </select>
        <div>
            <a href="formulario_tipo_recurso.php"  class="boton-agregar">Agregar Nuevo Tipo</a>
        </div>
    </div>

    <div>
        <label for="url">URL:</label>
        <input type="url" id="url" name="url" required placeholder="https://ejemplo.com/recurso">
    </div>

    <div>
        <label for="img">Imagen principal:</label>
        <input type="file" id="img" name="img" accept="image/*" required>
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Crear Recurso</button>
        <button class="cancelar" type="button" onclick="window.location.href='index.php'">Cancelar</button>
    </div>
</form>

<script>
$('#formRecurso').on('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
        url: '../controller/recursos/crear_recursos.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            let res = JSON.parse(response);
            Swal.fire({
                icon: res.status === 'success' ? 'success' : 'error',
                title: res.status === 'success' ? '¡Éxito!' : 'Error',
                text: res.message,
                confirmButtonColor: '#9d0707'
            }).then(() => {
                if (res.status === 'success') {
                    $('#formRecurso')[0].reset();
                    if (res.redirect_url) {
                        window.location.href = res.redirect_url;
                    }
                }
            });
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar el recurso.',
                confirmButtonColor: '#9d0707'
            });
        }
    });
});
</script>

<style>
    .button-group {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
    .btn-add-type, .btn-view-types {
        display: inline-block;
        padding: 8px 15px;
        background-color: #4CAF50; /* Green */
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-add-type:hover, .btn-view-types:hover {
        background-color: #45a049;
    }
</style>