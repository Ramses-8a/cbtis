<?php include_once('header.php'); ?>
<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Agregar Torneos</title>
</head>

<form id="formProyecto" action="guardar_torneo.php" method="POST" enctype="multipart/form-data" class="form-proyectos">

<form id="formTorneo" enctype="multipart/form-data">
    <div>
        <label for="nom_torneo">Nombre del Torneo:</label>
        <input type="text" id="nom_torneo" name="nom_torneo">
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
        <input type="file" id="img_proyecto" name="img_proyecto" accept="image/*">
        <div id="preview_principal" style="max-width: 200px; max-height: 200px; margin-top: 10px;"></div>
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion">
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

<script>
$(document).ready(function() {
    let imagenesSeleccionadas = [];
    const maxImagenes = 10;

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

    $('#img_adicionales').on('change', function() {
        const files = Array.from(this.files);
        const espacioDisponible = maxImagenes - imagenesSeleccionadas.length;

        if (files.length > espacioDisponible) {
            alert(`Solo puedes agregar ${espacioDisponible} imagen(es) más.`);
            this.value = '';
            return;
        }

        files.forEach(file => {
            if (imagenesSeleccionadas.length < maxImagenes) {
                const container = document.createElement('div');
                container.style.display = 'inline-block';
                container.style.margin = '5px';
                container.style.position = 'relative';

                const img = document.createElement('img');
                img.style.width = '150px';
                img.style.height = '150px';
                img.style.objectFit = 'contain';

                const btn = document.createElement('button');
                btn.textContent = 'X';
                btn.style.position = 'absolute';
                btn.style.top = '0';
                btn.style.right = '0';

                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);

                container.appendChild(img);
                container.appendChild(btn);
                document.getElementById('preview_adicionales').appendChild(container);

                imagenesSeleccionadas.push({ file: file, container: container });

                btn.onclick = function(e) {
                    e.preventDefault();
                    container.remove();
                    imagenesSeleccionadas = imagenesSeleccionadas.filter(i => i.container !== container);
                    actualizarContador();
                    $('#img_adicionales').val('');
                };
            }
        });

        actualizarContador();
        this.value = '';
    });

    function actualizarContador() {
        $('#contador_imagenes').text(`${imagenesSeleccionadas.length}/10 imágenes seleccionadas`);
    }

    $('#formTorneo').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Limpiar imágenes adicionales duplicadas del input
        for (let pair of formData.entries()) {
            if (pair[0] === 'img_adicionales[]') {
                formData.delete(pair[0]);
            }
        }

        imagenesSeleccionadas.forEach(img => {
            formData.append('img_adicionales[]', img.file);
        });

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
                        $('#preview_adicionales').empty();
                        imagenesSeleccionadas = [];
                        actualizarContador();
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
            error: function(xhr) {
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
