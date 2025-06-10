<?php include_once('header.php'); ?>
<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Subir recursos</title>
</head>

<form id="formRecurso" enctype="multipart/form-data" class="form-proyectos">
    <div>
        <label for="nom_recurso">Nombre del recurso:</label>
        <input type="text" id="nom_recurso" name="nom_recurso" required>
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
    </div>

    <div>
        <label for="pk_tipo_recurso">Tipo de recurso:</label>
        <select name="pk_tipo_recurso" id="pk_tipo_recurso" required>
            <?php
            include_once '../controller/conexion.php';
            $stmt = $connect->prepare("SELECT * FROM tipo_recursos WHERE estatus = 1");
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                echo "<option value='{$row['pk_tipo_recurso']}'>{$row['nom_tipo']}</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label for="url">URL:</label>
        <!-- Cambié type a url para validación básica en el navegador -->
        <input type="text" id="url" name="url" required>
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