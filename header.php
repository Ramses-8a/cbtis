<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo_sf.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg ">
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
                        <a class="nav-link dropdown-toggle text-white fw-bold" href="torneos_activos.php" role="button" data-bs-toggle="dropdown">Torneos</a>
                        <ul class="dropdown-menu">
                            <?php
                            require_once(__DIR__ . '/controller/conexion.php');
                            require_once(__DIR__ . '/controller/torneo/mostrar_tipo_torneos.php');

                            $tipos_torneo = getTiposTorneo($connect);
                            foreach ($tipos_torneo as $tipo): ?>
                                <li>
                                    <a class="dropdown-item" href="torneos_activos.php?tipo=<?= urlencode($tipo['nom_tipo']) ?>">
                                        <?= htmlspecialchars($tipo['nom_tipo']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-bold" href="#" role="button" data-bs-toggle="dropdown">Recursos Tecnológicos</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="mostrar_recursos.php">Tecnológicos</a></li>
                            <li><a class="dropdown-item" href="mostrar_cursos.php">Cursos</a></li>
                        </ul>
                    </li>

                    <li class="nav-item login-btn">
                        <a class="nav-link text-white fw-bold" href="admin/formulario_login.php">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>