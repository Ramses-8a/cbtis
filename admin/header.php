<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="img/logo.png">
        <!-- cdn de bootstrap NO BORRAR -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- SweetAlert2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
       
        <style>
            .navbar {
                background-color: #9d0707 !important;
            }
             .nav-link:hover {
                color: #ffdddd !important;
                
            }

            .dropdown-menu {
                background-color: #9d0707;
                border: none;
            }

            .dropdown-item {
                color: white;
            }

            .dropdown-item:hover {
                background-color: #7a0505;
                color: #fff;
            }
        </style>
    </head>
    <body>
       <nav class="navbar navbar-expand-lg border-bottom border-body">
    <div class="container-fluid">
       
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="./img/logo_cbtis.png" alt="logo" width="90" height="90" style="border-radius: 50%;">
            <h2 class="ms-2 text-white fw-bold">CBTis No.152</h2>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

    
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="mostrar_proyectos.php">Proyectos</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-bold" href="#" role="button" data-bs-toggle="dropdown">Torneos</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"  href="torneo1">Software</a></li>
                        <li><a class="dropdown-item" href="torneo2">Videojuegos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-bold" href="#" role="button" data-bs-toggle="dropdown">Recursos Tecnológicos</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="1">Tecnológicos</a></li>
                        <li><a class="dropdown-item" href="2">Cursos</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


 <!-- cdn de bootstrap NO BORRAR -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>