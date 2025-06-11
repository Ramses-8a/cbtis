<?php
 include_once('header.php'); 
 ?>
<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Agregar Torneos</title>
</head>

<form id="formTorneo" action="guardar_torneo.php" method="POST" enctype="multipart/form-data" class="form-proyectos">
    <div>
        <label for="nom_torneo">Nombre del Torneo:</label>
        <input type="text" id="nom_torneo" name="nom_torneo" required>
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
        <input type="file" id="img_proyecto" name="img_proyecto" accept="image/*" required>
        <div id="preview_principal" style="max-width: 200px; max-height: 200px; margin-top: 10px;"></div>
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required>
    </div>

    <div>
        <label for="detalles">Detalles del Torneo:</label>
        <input type="text" id="detalles" name="detalles" required>
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Crear Torneos</button>
        <button class="cancelar" type="button" onclick="window.location.href='index.php'">Cancelar</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#img_proyecto').on('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('preview_principal');
        preview.innerHTML = '';
        if (file) {
            const img = document.createElement('img');
            img.style.maxWidth = '200px';
            img.style.maxHeight = '200px';
            img.style.objectFit = 'contain';
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
            preview.appendChild(img);
        }
    });

    $('#formTorneo').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: 'guardar_torneo.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Torneo creado!',
                        text: res.message,
                        confirmButtonColor: '#9d0707'
                    }).then(() => {
                        $('#formTorneo')[0].reset();
                        $('#preview_principal').empty();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                        confirmButtonColor: '#9d0707'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud.',
                    confirmButtonColor: '#9d0707'
                });
            }
        });
    });
});
</script>
