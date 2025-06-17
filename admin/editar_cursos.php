<?php
include_once('header.php');
include('../controller/cursos/buscar_curso.php');

if (!isset($_GET['pk_curso'])) {
    header('Location: lista_cursos.php');
    exit;
}

$curso = $curso ?? null;

?>
<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Editar Curso</title>
    <style>
        .image-preview-container {
            position: relative;
            display: inline-block;
            margin-right: 10px; /* Add some space to the right of the image */
            border: 1px solid #ddd; /* Add a subtle border around the image */
            border-radius: 4px;
            overflow: hidden; /* Ensure the image doesn't overflow the border-radius */
        }

        .image-preview-container img {
            display: block; /* Remove extra space below the image */
            max-width: 100px; /* Ensure image fits within the container */
            height: auto;
        }

        .delete-image-icon {
            position: absolute;
            top: 5px; /* Adjust position to be slightly inside the top-right corner */
            right: 5px;
            background-color: rgba(255, 0, 0, 0.8); /* Slightly more opaque red */
            color: white;
            border-radius: 50%;
            width: 24px; /* Slightly larger icon */
            height: 24px;
            text-align: center;
            line-height: 24px; /* Adjust line-height for vertical alignment */
            font-size: 16px; /* Larger font size for the 'x' */
            font-weight: bold; /* Make the 'x' bolder */
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s ease; /* Smooth transition on hover */
        }

        .delete-image-icon:hover {
            background-color: rgba(255, 0, 0, 1); /* Fully opaque red on hover */
            transform: scale(1.1); /* Slightly enlarge on hover */
        }
    </style>
</head>

<form id="formCurso" enctype="multipart/form-data" class="form-proyectos">
    <input type="hidden" id="pk_curso" name="pk_curso" value="<?= $curso['pk_curso'] ?? '' ?>">
    <div>
        <label for="nom_curso">Nombre del curso:</label>
        <input type="text" id="nom_curso" name="nom_curso" value="<?= $curso['nom_curso'] ?? '' ?>" required>
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?= $curso['descripcion'] ?? '' ?></textarea>
    </div>

    <div>
        <label for="fk_tipo_curso">Tipo de curso:</label>
        <select name="fk_tipo_curso" id="fk_tipo_curso" required>
            <?php
            include_once '../controller/conexion.php';
            $stmt = $connect->prepare("SELECT * FROM tipo_cursos WHERE estatus = 1");
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $selected = (isset($curso['fk_tipo_curso']) && $curso['fk_tipo_curso'] == $row['pk_tipo_curso']) ? 'selected' : '';
                echo "<option value='{$row['pk_tipo_curso']}' {$selected}>{$row['nom_tipo']}</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label for="fk_lenguaje">Lenguaje:</label>
        <select name="fk_lenguaje" id="fk_lenguaje" required>
            <?php
            include_once '../controller/conexion.php';
            $stmt = $connect->prepare("SELECT * FROM lenguajes ");
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $selected = (isset($curso['fk_lenguaje']) && $curso['fk_lenguaje'] == $row['pk_lenguaje']) ? 'selected' : '';
                echo "<option value='{$row['pk_lenguaje']}' {$selected}>{$row['nom_lenguaje']}</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label for="link">URL del curso:</label>
        <input type="text" id="link" name="link" value="<?= $curso['link'] ?? '' ?>" required>
    </div>

    <div>
        <label for="img">Imagen principal(OPCIONAL):</label>
        <?php if (isset($curso['img']) && $curso['img']): ?>
            <div class="image-preview-container">
                <img src="../uploads/<?= $curso['img'] ?>" width="100px" id="current_image_preview">
                <span class="delete-image-icon" id="delete_img_icon" data-pk-curso="<?= $curso['pk_curso'] ?>" data-img-name="<?= $curso['img'] ?>">&times;</span>
            </div>
            <input type="hidden" name="current_img" value="<?= $curso['img'] ?>">
        <?php endif; ?>
        <input type="file" id="img" name="img" accept="image/*">
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Actualizar Curso</button>
        <button class="cancelar" type="button" onclick="window.location.href='lista_cursos.php'">Cancelar</button>
    </div>
</form>

<script>
console.log('PK Curso recibido:', <?php echo json_encode($_GET['pk_curso'] ?? 'No PK Curso'); ?>);

document.addEventListener('DOMContentLoaded', function() {
    const deleteImgIcon = document.getElementById('delete_img_icon');
    if (deleteImgIcon) {
        deleteImgIcon.addEventListener('click', function() {
            const pk_curso = this.dataset.pkCurso;
            const img_name = this.dataset.imgName;
            const icon = this; // Reference to the icon

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarla!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../controller/cursos/eliminar_imagen_curso.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `pk_curso=${pk_curso}&img_name=${img_name}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire(
                                '¡Eliminada!',
                                'La imagen ha sido eliminada.',
                                'success'
                            );
                            // Hide the image and the delete icon
                            const imagePreviewContainer = icon.closest('.image-preview-container');
                            if (imagePreviewContainer) {
                                imagePreviewContainer.style.display = 'none';
                            }
                            // Clear the hidden input for current_img so it's not sent on form submit
                            const currentImgInput = document.querySelector('input[name="current_img"]');
                            if (currentImgInput) {
                                currentImgInput.value = '';
                            }
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error en la petición:', error);
                        Swal.fire(
                            'Error!',
                            'Error al comunicarse con el servidor.',
                            'error'
                        );
                    });
                }
            });
        });
    }
});

document.getElementById('formCurso').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // Remove delete_img from formData if it exists, as it's now handled by direct AJAX
    if (formData.has('delete_img')) {
        formData.delete('delete_img');
    }

    fetch('../controller/cursos/actualizar_curso.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log('Respuesta del servidor:', text);
        try {
            const data = JSON.parse(text);
            console.log('Datos parseados:', data);
            
            Swal.fire({
                icon: data.status === 'success' ? 'success' : data.status === 'warning' ? 'warning' : 'error',
                title: data.status === 'success' ? '¡Éxito!' : data.status === 'warning' ? 'Advertencia' : 'Error',
                text: data.message,
                confirmButtonColor: '#9d0707'
            }).then(() => {
                if (data.status === 'success' && data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            });
        } catch (e) {
            console.error('Error al parsear JSON:', e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar la respuesta del servidor',
                html: `<pre>${text}</pre>`,
                confirmButtonColor: '#9d0707'
            });
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al comunicarse con el servidor',
            confirmButtonColor: '#9d0707'
        });
    });
});
</script>