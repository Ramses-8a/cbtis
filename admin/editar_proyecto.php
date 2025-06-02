<?php
include_once('header.php');
include('../controller/proyecto/buscar_proyecto.php');

if (!isset($_GET['pk_proyecto'])) {
    header('Location: lista_proyectos.php');
    exit;
}

$num_imagenes = count($proyecto['imagenes_adicionales']);

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
        <img id="img-preview" src="../img/<?= $proyecto['img_proyecto'] ?>" style="max-width: 200px; max-height: 200px; object-fit: contain;">
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
    
    <a href="formulario_fotos.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>">Agregar nuevas fotos</a>

    <button type="submit">Guardar Cambios</button>
</form>

<script>
$(document).ready(function() {
    // Previsualización de imagen principal
    $('input[name="img_proyecto"]').on('change', function() {
        const file = this.files[0];
        const imgPreview = $('#img-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.attr('src', e.target.result);
                imgPreview.show();
            };
            reader.readAsDataURL(file);
        } else {
            imgPreview.attr('src', '../img/' + '<?= $proyecto["img_proyecto"] ?>');
        }
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
                        text: response.message
                    }).then(() => {
                        window.location.reload();
                    });
                } else if (response.status === 'warning') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al actualizar el proyecto'
                });
            }
        });
    });
});
</script>