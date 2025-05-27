<?php
include_once('header.php');
include('../controller/proyecto/buscar_proyecto.php');

if (!isset($_GET['pk_proyecto'])) {
    header('Location: lista_proyectos.php');
    exit;
}
?>

<form id="formEditar" enctype="multipart/form-data">
    <input type="hidden" name="pk_proyecto" value="<?= $proyecto['pk_proyecto'] ?>">
    
    <div>
        <label>Nombre del Proyecto:</label>
        <input type="text" name="nom_proyecto" value="<?= $proyecto['nom_proyecto'] ?>" required>
    </div>
    
    <div>
        <label>Descripción:</label>
        <textarea name="descripcion" required><?= $proyecto['descripcion'] ?></textarea>
    </div>
    
    <div>
        <label>Detalles:</label>
        <textarea name="detalles" required><?= $proyecto['detalles'] ?></textarea>
    </div>

    <div>
        <label>Url:</label>
        <textarea name="url" required><?= $proyecto['url'] ?></textarea>
    </div>
    
    <div>
        <label>Imagen Principal:</label>
        <img src="../img/<?= $proyecto['img_proyecto'] ?>" style="max-width: 200px; max-height: 200px; object-fit: contain;">
        <input type="file" name="img_proyecto" accept="image/*">
        <small>Dejar vacío para mantener la imagen actual</small>
    </div>

    <div>
        <label>Imágenes Adicionales Actuales:</label>
        <div id="imagenes_actuales" style="margin: 10px 0;">
            <?php if (!empty($proyecto['imagenes_adicionales'])): ?>
                <?php foreach ($proyecto['imagenes_adicionales'] as $img): ?>
                    <div style="display: inline-block; margin: 5px; position: relative;">
                        <img src="../img/<?= $img['img'] ?>" style="width: 150px; height: 150px; object-fit: contain;">
                        <button type="button" class="eliminar-imagen" data-id="<?= $img['pk_img_proyectos'] ?>" style="position: absolute; top: 0; right: 0;">X</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay imágenes adicionales</p>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <label>Agregar Nuevas Imágenes (Máximo 10 en total):</label>
        <input type="file" id="img_adicionales" name="img_adicionales[]" accept="image/*" multiple>
        <span id="contador_imagenes">0/10 imágenes seleccionadas</span>
        <div id="preview_adicionales" style="margin-top: 10px;"></div>
    </div>
    
    <button type="submit">Guardar Cambios</button>
</form>

<script>
$(document).ready(function() {
    let imagenesSeleccionadas = new Array();
    const maxImagenes = 10;
    const imagenesActuales = <?= json_encode($proyecto['imagenes_adicionales'] ?? []) ?>;

    function actualizarContador() {
        const total = imagenesActuales.length + imagenesSeleccionadas.length;
        $('#contador_imagenes').text(`${total}/10 imágenes seleccionadas`);
    }

    // Previsualización de imagen principal
    $('input[name="img_proyecto"]').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('img[src*="' + $proyecto['img_proyecto'] + '"]').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Previsualización de imágenes adicionales
    $('#img_adicionales').on('change', function() {
        const files = Array.from(this.files);
        const espacioDisponible = maxImagenes - imagenesActuales.length - imagenesSeleccionadas.length;

        if (files.length > espacioDisponible) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Solo puedes agregar ${espacioDisponible} imagen(es) más. Ya tienes ${imagenesActuales.length + imagenesSeleccionadas.length} seleccionada(s)`
            });
            this.value = '';
            return;
        }

        files.forEach(file => {
            if (imagenesSeleccionadas.length < maxImagenes) {
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
                deleteBtn.style.background = 'red';
                deleteBtn.style.color = 'white';
                deleteBtn.style.border = 'none';
                deleteBtn.style.cursor = 'pointer';
                deleteBtn.style.padding = '2px 6px';
                deleteBtn.style.borderRadius = '3px';

                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);

                imgContainer.appendChild(img);
                imgContainer.appendChild(deleteBtn);
                document.getElementById('preview_adicionales').appendChild(imgContainer);

                imagenesSeleccionadas.push({
                    container: imgContainer,
                    file: file
                });

                deleteBtn.onclick = function(e) {
                    e.preventDefault();
                    imgContainer.remove();
                    imagenesSeleccionadas = imagenesSeleccionadas.filter(img => img.container !== imgContainer);
                    actualizarContador();
                };
            }
        });

        actualizarContador();
        this.value = '';
    });

    // Eliminar imagen existente
    $('.eliminar-imagen').on('click', function() {
        const imgId = $(this).data('id');
        const imgContainer = $(this).parent();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/proyecto/eliminar_imagen.php',
                    type: 'POST',
                    data: { pk_img_proyectos: imgId },
                    success: function(response) {
                        if (response.status === 'success') {
                            imgContainer.remove();
                            const index = imagenesActuales.findIndex(img => img.pk_img_proyectos === imgId);
                            if (index > -1) {
                                imagenesActuales.splice(index, 1);
                            }
                            actualizarContador();
                            Swal.fire('¡Eliminada!', 'La imagen ha sido eliminada.', 'success');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Hubo un error al eliminar la imagen', 'error');
                    }
                });
            }
        });
    });

    // Envío del formulario
    $('#formEditar').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: '../controller/proyecto/actualizar_proyecto.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Proyecto actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else if (response.status === 'info') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        text: response.message
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Hubo un error al actualizar el proyecto', 'error');
            }
        });
    });

    // Inicializar contador
    actualizarContador();
});
</script>