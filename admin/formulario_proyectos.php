<?php 
include_once('header.php');
?>
<head>
     <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Subir proyectos</title>
</head>
 <div class="con_volver">
        <a href="lista_proyectos.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Proyectos</h3>
        </div>

<form id="formProyecto" enctype="multipart/form-data" class="form-proyectos">
    <div>
        <label for="nom_proyecto">Nombre del Proyecto:</label>
        <input type="text" id="nom_proyecto" name="nom_proyecto" required>
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
    </div>

    <div>
        <label for="detalles">Detalles:</label>
        <textarea id="detalles" name="detalles" required></textarea>
    </div>

    <div>
        <label for="url">Url:</label>
       <input type="text" id="url" name="url" required>
    </div>

    <div>
        <label for="img_proyecto">Imagen Principal del Proyecto:</label>
        <input type="file" id="img_proyecto" name="img_proyecto" accept="image/*" required>
        <div id="preview_principal" style="max-width: 200px; max-height: 200px; margin-top: 10px;"></div>
    </div>

    <div>
        <label>Imágenes Adicionales:</label>
        <div>
            <input type="file" id="img_adicionales" name="img_adicionales[]" accept="image/*" multiple>
            <span id="contador_imagenes">0/10 imágenes seleccionadas</span>
        </div>
        <div id="preview_adicionales" style="margin-top: 10px;"></div>
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Crear Proyecto</button>
        <button class="cancelar" type="submit" onclick="window.location.href='index.php'">Cancelar</button>
    </div>
</form>

<script>
$(document).ready(function() {
    let imagenesSeleccionadas = new Array();
    const maxImagenes = 10;

    // Previsualización de imagen principal
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

    // Previsualización de imágenes adicionales
    $('#img_adicionales').on('change', function() {
        const files = Array.from(this.files);
        const espacioDisponible = maxImagenes - imagenesSeleccionadas.length;

        if (files.length > espacioDisponible) {
            alert(`Solo puedes agregar ${espacioDisponible} imagen(es) más. Ya tienes ${imagenesSeleccionadas.length} seleccionada(s)`);
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
                deleteBtn.style.cursor = 'pointer';

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
                    $('#img_adicionales').val('');
                };
            }
        });

        actualizarContador();
        this.value = '';
    });

    function actualizarContador() {
        document.getElementById('contador_imagenes').textContent = 
            `${imagenesSeleccionadas.length}/10 imágenes seleccionadas`;
    }

    $('#formProyecto').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Limpiar las imágenes adicionales anteriores del FormData
        for (let pair of formData.entries()) {
            if (pair[0] === 'img_adicionales[]') {
                formData.delete(pair[0]);
            }
        }

        // Agregar las imágenes seleccionadas al FormData
        imagenesSeleccionadas.forEach(img => {
            formData.append('img_adicionales[]', img.file);
        });

        $.ajax({
            url: '../controller/proyecto/crear_proyecto.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                let res = JSON.parse(response);
// ... existing code ...
if(res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: res.message,
                        confirmButtonColor: '#9d0707'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = res.redirect_url;
                        }
                    });
                } else if (res.status === 'warning') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: res.message,
                        confirmButtonColor: '#9d0707'
                    });
                } else if (res.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                        confirmButtonColor: '#9d0707'
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        text: res.message || 'Respuesta desconocida del servidor',
                        confirmButtonColor: '#9d0707'
                    });
                }
            },
            error: function(xhr) {
                let responseText = xhr.responseText;
                try {
                    let res = JSON.parse(responseText);
                    let icon = 'error';
                    let title = 'Error';

                    if (res.status === 'warning') {
                        icon = 'warning';
                        title = 'Advertencia';
                    } else if (res.status === 'info') {
                        icon = 'info';
                        title = 'Información';
                    }

                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: res.message || 'Ocurrió un error inesperado',
                        confirmButtonColor: '#9d0707'
                    });
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud',
                        confirmButtonColor: '#9d0707'
                    });
                }
            }
        });
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