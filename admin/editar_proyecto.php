<?php
include_once('header.php');
include('../controller/proyecto/buscar_proyecto.php');

if (!isset($_GET['pk_proyecto'])) {
    header('Location: lista_proyectos.php');
    exit;
}

$stmt = $connect->prepare("SELECT COUNT(*) FROM img_proyectos WHERE fk_proyecto = ?");
$stmt->execute([$proyecto['pk_proyecto']]);
$num_imagenes = $stmt->fetchColumn();

?>

<head>
    <link rel="stylesheet" href="../css/form_editar.css">
</head>

<div class="con_volver">
        <a href="lista_proyectos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Proyectos</h3>
        </div>

<form id="formEditar" enctype="multipart/form-data" class="form-editar">
    <input type="hidden" name="pk_proyecto" value="<?= $proyecto['pk_proyecto'] ?>">
    
    <div class="nom-proyecto">
        <label>Nombre del Proyecto:</label>
        <input type="text" name="nom_proyecto" value="<?= $proyecto['nom_proyecto'] ?>" required>
    </div>
    
    <div class="desc-proyecto">
        <label>Descripción:</label>
        <textarea name="descripcion" required><?= $proyecto['descripcion'] ?></textarea>
    </div>
    
    <div class="detalles-proyecto">
        <label>Detalles:</label>
        <textarea name="detalles" required><?= $proyecto['detalles'] ?></textarea>
    </div>

    <div class="url-proyecto">
        <label>Url:</label>
        <textarea name="url" required><?= $proyecto['url'] ?></textarea>
    </div>
    
    <div class="img-proyecto">
        <label>Imagen Principal:</label>
        <img id="img-preview" src="../uploads/<?= $proyecto['img_proyecto'] ?>" style="max-width: 200px; max-height: 200px; object-fit: contain;">
        <input type="file" name="img_proyecto" accept="image/*">
        <small>Dejar vacío para mantener la imagen actual</small>
    </div>

    <div class="imgads-proyecto">
        <label>Imágenes Adicionales Actuales:</label>
        <div id="imagenes_actuales" style="margin: 10px 0;">
            <?php if (!empty($proyecto['imagenes_adicionales'])): ?>
                <?php foreach ($proyecto['imagenes_adicionales'] as $img): ?>
                    <div class="img-adicional" data-id="<?= $img['pk_img_proyectos'] ?>" style="display: inline-block; margin: 5px; position: relative;">
                        <img src="../uploads/<?= $img['img'] ?>" style="width: 150px; height: 150px; object-fit: contain;">
                        <button type="button" class="eliminar-imagen" data-id="<?= $img['pk_img_proyectos'] ?>" style="position: absolute; top: 0; right: 0;">X</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay imágenes adicionales</p>
            <?php endif; ?>
        </div>
        <input type="file" id="img_adicionales" name="img_adicionales[]" accept="image/*" multiple style="margin-top:10px;">
        <div id="preview_adicionales" style="margin-top: 10px;"></div>
    </div>
    
    <!-- Botón de agregar nuevas fotos eliminado -->

    <div class="button-container">
        <button class="guardar" type="submit">Actualizar Proyecto</button>
        <button class="cancelar" type="submit" onclick="window.location.href='lista_proyectos.php'">Cancelar</button>
    </div>
    <!-- despues de guardar que te mande a la tabla "lista_proyectos.php" -->
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
            imgPreview.attr('src', '../uploads/' + '<?= $proyecto["img_proyecto"] ?>');
        }
    });

    // --- NUEVO MANEJO DE IMÁGENES ADICIONALES ---
    let imagenesAEliminar = [];
    let nuevasImagenes = [];
    const maxImagenes = 10;
    const inputAdicionales = $('#img_adicionales');
    const previewAdicionales = $('#preview_adicionales');
    const imagenesActualesDiv = $('#imagenes_actuales');

    // Eliminar imagen adicional (solo en frontend hasta guardar) con SweetAlert
    imagenesActualesDiv.on('click', '.eliminar-imagen', function(e) {
        e.preventDefault();
        const imgId = $(this).data('id');
        const $imgDiv = $(this).parent();
        Swal.fire({
            title: '¿Estás seguro de eliminar esta foto?',
            text: 'Los cambios se harán cuando actualices el proyecto.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $imgDiv.hide();
                if (!imagenesAEliminar.includes(imgId)) imagenesAEliminar.push(imgId);
            }
        });
    });

    // Previsualizar nuevas imágenes
    inputAdicionales.on('change', function() {
        const files = Array.from(this.files);
        if ((nuevasImagenes.length + files.length) > maxImagenes) {
            Swal.fire('Límite', 'Solo puedes agregar hasta 10 imágenes en total.', 'warning');
            return this.value = '';
        }
        files.forEach(file => {
            if (nuevasImagenes.length >= maxImagenes) return;
            nuevasImagenes.push(file);
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = $('<div style="display:inline-block; margin:5px; position:relative;"></div>');
                const img = $('<img style="width:150px; height:150px; object-fit:contain;">').attr('src', e.target.result);
                const btn = $('<button type="button" style="position:absolute;top:0;right:0;">X</button>');
                btn.on('click', function(ev) {
                    ev.preventDefault();
                    imgContainer.remove();
                    nuevasImagenes = nuevasImagenes.filter(f => f !== file);
                });
                imgContainer.append(img).append(btn);
                previewAdicionales.append(imgContainer);
            };
            reader.readAsDataURL(file);
        });
        this.value = '';
    });

    // Envío del formulario
    $('#formEditar').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        // Remover el input file original para evitar conflictos
        formData.delete('img_adicionales[]');
        
        // Adjuntar imágenes a eliminar
        imagenesAEliminar.forEach(id => formData.append('imagenes_a_eliminar[]', id));
        
        // Adjuntar nuevas imágenes
        nuevasImagenes.forEach(file => formData.append('img_adicionales[]', file));
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
                    }).then((result) => {
                        if (result.isConfirmed && response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            window.location.reload();
                        }
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

    // Si se da click en cancelar, recargar la página para descartar cambios
    $('.cancelar').on('click', function(e) {
        e.preventDefault();
        window.location.href = 'lista_proyectos.php';
    });
});
</script>


<style>
    /* contenedor para volver */
.con_volver {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px 20px;
  background-color: white;
}

.con_volver .volver img {
  width: 28px;
  height: 28px;
  object-fit: contain;
  cursor: pointer;
}

.con_volver h3 {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
}
</style>