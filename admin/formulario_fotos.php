<?php
include_once('header.php');

if (!isset($_GET['pk_proyecto'])) {
    header('Location: lista_proyectos.php');
    exit;
}

$id = $_GET['pk_proyecto'];
?>

<!-- <div class="con_volver">
        <a href="editar_proyecto.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Proyectos</h3>
        </div> -->

<form action="../controller/proyecto/agregar_nuevas_fotos.php" method="post" id="formProyecto" enctype="multipart/form-data" class="form-ftsadd">
    <!-- <label>Imágenes Adicionales:</label> -->
    <div class="fotos-add">
        <input type="hidden" id="pk_proyecto" name="pk_proyecto" value="<?= $id ?>" >

        <input type="file" id="img_adicionales" name="img_adicionales[]" accept="image/*" multiple>
        <span id="contador_imagenes">0/10 imágenes seleccionadas</span>
    </div>
    <div id="preview_adicionales" style="margin-top: 10px;"></div>

    <div class="btn-listo">
    <button type="submit" >Listo</button>
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

<style>
    /* Estilo formal blanco y rojo para formulario */
.form-ftsadd {
    background-color: #ffffff;
    border: 2px solid #dc2626;
    border-radius: 12px;
    padding: 30px;
    max-width: 500px;
    margin: 20px auto;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Contenedor de fotos */
.fotos-add {
    margin-bottom: 25px;
    text-align: center;
}

/* Estilo del input file */
#img_adicionales {
    display: none;
}

/* Label personalizado para el input file */
.fotos-add::before {
    content: "Seleccionar Imágenes";
    display: inline-block;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    text-transform: uppercase;
}

.fotos-add:hover::before {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
}

/* Estilo del contador */
#contador_imagenes {
    display: block;
    margin-top: 15px;
    color: #dc2626;
    font-size: 13px;
    font-weight: 500;
    padding: 8px 16px;
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    max-width: fit-content;
    margin-left: auto;
    margin-right: auto;
}

/* Preview de imágenes */
#preview_adicionales {
    margin-top: 20px !important;
    padding: 15px;
    background-color: #f9fafb;
    border: 1px dashed #dc2626;
    border-radius: 8px;
    min-height: 60px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    align-items: center;
}

#preview_adicionales:empty::before {
    content: "Vista previa de imágenes";
    color: #9ca3af;
    font-style: italic;
    font-size: 14px;
}

/* Contenedor del botón */
.btn-listo {
    text-align: center;
    margin-top: 25px;
}

/* Estilo del botón */
.btn-listo button {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    color: #dc2626;
    border: 2px solid #dc2626;
    padding: 12px 30px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 120px;
}

.btn-listo button:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
}

.btn-listo button:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(220, 38, 38, 0.2);
}

/* Efectos de focus para accesibilidad */
.btn-listo button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
}

/* Responsive design */
@media (max-width: 768px) {
    .form-ftsadd {
        margin: 15px;
        padding: 20px;
        max-width: none;
    }
    
    .fotos-add::before {
        padding: 10px 20px;
        font-size: 13px;
    }
    
    .btn-listo button {
        padding: 10px 25px;
        font-size: 14px;
    }
}

/* Animación sutil para el formulario */
.form-ftsadd {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

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
