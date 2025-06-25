<?php
include_once('header.php'); 
include_once '../controller/conexion.php';
?>
<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Agregar Curso</title>
</head>

<form id="formCurso" action="../controller/cursos/guardar_curso.php" method="POST" enctype="multipart/form-data" class="form-proyectos">
    
    <div>
        <label for="nom_curso">Nombre del Curso:</label>
        <input type="text" id="nom_curso" name="nom_curso" required>
    </div>

    <div>
        <label for="fk_tipo_curso">Tipo de Curso:</label>
        <select name="fk_tipo_curso" required>
            <option value="" disabled selected>Selecciona una opción</option>
            <?php
            $stmt = $connect->prepare("SELECT pk_tipo_curso, nom_tipo FROM tipo_cursos WHERE estatus = 1");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                echo "<option value='{$row['pk_tipo_curso']}'>{$row['nom_tipo']}</option>";
            }
            ?>
        </select>
    </div>

    <div>
            <a href="formulario_tipo_curso.php" class="boton-agregar">Agregar Nuevo Tipo</a>
    </div>

    <div>
        <label for="fk_lenguaje">Lenguaje:</label>
        <select name="fk_lenguaje" required>
            <option value="" disabled selected>Selecciona un lenguaje</option>
            <?php
            $stmt = $connect->prepare("SELECT pk_lenguaje, nom_lenguaje FROM lenguajes");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                echo "<option value='{$row['pk_lenguaje']}'>{$row['nom_lenguaje']}</option>";
            }
            ?>
        </select>
    </div>

    <div>
            <a href="formulario_tipo_lenguaje.php" class="boton-agregar">Agregar Nuevo Tipo</a>
    </div>

    <div>
        <label for="link">Link del Curso:</label>
        <input type="url" id="link" name="link" required>
    </div>
    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
    </div>


    <div>
        <label for="img_curso">Imagen del Curso (opcional):</label>
        <input type="file" id="img_curso" name="img_curso" accept="image/*">
        <div id="preview_img" style="max-width: 200px; max-height: 200px; margin-top: 10px;"></div>
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Crear Curso</button>
        <button class="cancelar" type="button" onclick="window.location.href='index.php'">Cancelar</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    
    $('#img_curso').on('change', function() {
        const file = this.files[0];
        const preview = $('#preview_img').empty();

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('<img>', {
                    src: e.target.result,
                    css: {
                        maxWidth: '200px',
                        maxHeight: '200px',
                        objectFit: 'contain'
                    }
                }).appendTo(preview);
            };
            reader.readAsDataURL(file);
        }
    });

   
    $('#formCurso').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '../controller/cursos/guardar_curso.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Respuesta:", response);

                try {
                    const res = typeof response === "string" ? JSON.parse(response) : response;

                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Curso creado!',
                            text: res.message,
                            confirmButtonColor: '#9d0707'
                        }).then(() => {
                            window.location.href = 'lista_cursos.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                            confirmButtonColor: '#9d0707'
                        });
                    }
                } catch (error) {
                    console.error("Error al parsear JSON:", error);
                    console.log("Respuesta original:", response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Respuesta inválida',
                        text: 'El servidor no devolvió un JSON válido.',
                        confirmButtonColor: '#9d0707'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo enviar el formulario.',
                    confirmButtonColor: '#9d0707'
                });
            }
        });
    });
});
</script>

