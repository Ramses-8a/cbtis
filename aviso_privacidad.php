<?php
include_once 'header.php'; // Asegúrate de que este header incluya Bootstrap y Font Awesome
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Privacidad - CBTI's No. 152</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/aviso_privacidad.css">
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-lg p-lg-5 p-md-4 p-3 border-0">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4 display-5 fw-bold text-danger">Aviso de Privacidad</h2>
                        <p class="text-center text-muted mb-4">
                            Aquí puedes consultar nuestro aviso de privacidad completo.
                            También puedes descargarlo para una mejor lectura.
                        </p>

                        <div class="text-center mb-5">
                            <a href="archivos/LINEAMIENTOS GENERALES DE PROTECCIÓN DE DATOS PERSONALES.pdf" 
                               class="btn btn-outline-danger btn-lg rounded-pill shadow-sm" download="Aviso_de_Privacidad_CBTIS152.pdf">
                                <i class="fas fa-file-pdf me-2"></i>Descargar Aviso de Privacidad
                            </a>
                        </div>

                        <div class="iframe-container shadow-lg rounded">
                            <iframe src="archivos/LINEAMIENTOS GENERALES DE PROTECCIÓN DE DATOS PERSONALES.pdf" 
                                    title="Aviso de Privacidad del CBTI's No. 152" 
                                    frameborder="0" 
                                    allowfullscreen
                            ></iframe>
                        </div>

                        <p class="text-center text-muted mt-5">
                            Para cualquier duda o aclaración sobre nuestro aviso de privacidad, por favor contáctanos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>