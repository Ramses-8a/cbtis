<?php
include_once('header.php');

if (!isset($_GET['pk_proyecto'])) {
    header('Location: lista_proyectos.php');
    exit;
}

$id = $_GET['pk_proyecto'];
?>

<form action="../controller/proyecto/agregar_nuevas_fotos.php" method="post" id="formProyecto" enctype="multipart/form-data">
    <div>
    <label>Imágenes Adicionales:</label>
    <div>
        <input type="" id="pk_proyecto" name="pk_proyecto" value="<?= $id ?>" >

        <input type="file" id="img_adicionales" name="img_adicionales[]" accept="image/*" multiple>
        <span id="contador_imagenes">0/10 imágenes seleccionadas</span>
    </div>
    <div id="preview_adicionales" style="margin-top: 10px;"></div>
    </div>

    <div>
    <button type="submit">Crear Proyecto</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const maxImagenes = 10;
    let imagenesSeleccionadas = [];

    const inputImagenes = document.getElementById('img_adicionales');
    const preview = document.getElementById('preview_adicionales');
    const contador = document.getElementById('contador_imagenes');
    const form = document.getElementById('formProyecto');

    inputImagenes.addEventListener('change', function () {
        const files = Array.from(this.files);
        const disponibles = maxImagenes - imagenesSeleccionadas.length;

        if (files.length > disponibles) {
            alert(`Solo puedes agregar ${disponibles} imagen(es) más.`);
            return this.value = '';
        }

        files.forEach(file => {
            if (imagenesSeleccionadas.length >= maxImagenes) return;

            const imgContainer = document.createElement('div');
            imgContainer.style.display = 'inline-block';
            imgContainer.style.margin = '5px';
            imgContainer.style.position = 'relative';

            const img = document.createElement('img');
            img.style.width = '150px';
            img.style.height = '150px';
            img.style.objectFit = 'contain';

            const deleteBtn = document.createElement('button');
            deleteBtn.textContent = 'X';
            deleteBtn.style.position = 'absolute';
            deleteBtn.style.top = '0';
            deleteBtn.style.right = '0';
            deleteBtn.style.cursor = 'pointer';

            const reader = new FileReader();
            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);

            deleteBtn.onclick = (e) => {
                e.preventDefault();
                imgContainer.remove();
                imagenesSeleccionadas = imagenesSeleccionadas.filter(f => f !== file);
                actualizarContador();
            };

            imgContainer.appendChild(img);
            imgContainer.appendChild(deleteBtn);
            preview.appendChild(imgContainer);

            imagenesSeleccionadas.push(file);
        });

        actualizarContador();
        this.value = ''; // Limpiar input para poder volver a subir mismas imágenes
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('pk_proyecto', document.getElementById('pk_proyecto').value);

        imagenesSeleccionadas.forEach(file => {
            formData.append('img_adicionales[]', file);
        });

        fetch('../controller/proyecto/agregar_nuevas_fotos.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: res.message
                }).then(() => {
                    form.reset();
                    preview.innerHTML = '';
                    imagenesSeleccionadas = [];
                    actualizarContador();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar la solicitud.'
            });
        });
    });

    function actualizarContador() {
        contador.textContent = `${imagenesSeleccionadas.length}/10 imágenes seleccionadas`;
    }
});
</script>
