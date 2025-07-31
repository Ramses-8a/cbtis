<?php 
    include_once('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBTis No.152 - Especialidad en Programación</title>
    <link rel="stylesheet" href="css/index.css">
    </head>
<body class="bg-animated">

    <header class="hero-header">
        <div class="hero-content">
            <div class="container">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-code text-white"></i> 
                </h1>
                <div class="typing-animation code-font display-6 mb-4">
                    Especialidad en Programación
                </div>
                <!-- <p class="lead mb-4">Formando los desarrolladores del futuro con tecnología de vanguardia</p> -->
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="index.php#explorar" class="interactive-btn">
                        <i class="fas fa-rocket me-2"></i>Explorar Especialidad
                    </a>
                    <a href="mostrar_proyectos.php" class="interactive-btn">
                        <i class="fas fa-code-branch me-2"></i>Ver Proyectos
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container my-5">
        <div class="code-block floating">
            <div class="code-content">
                <span style="color: #64748b;">// Bienvenido a la especialidad más innovadora</span><br>
                <span style="color: #f59e0b;">class</span> <span style="color: #06b6d4;">CBTisProgramacion</span> {<br>
                &nbsp;&nbsp;<span style="color: #10b981;">constructor</span>() {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f3f4f6;">this.mision = </span><span style="color: #fbbf24;">"Formar programadores excepcionales"</span>;<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f3f4f6;">this.vision = </span><span style="color: #fbbf24;">"Liderar la innovación tecnológica"</span>;<br>
                &nbsp;&nbsp;}<br>
                }
            </div>
        </div>
    </div>

    <section id="explorar" class="container my-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-white mb-3">¿Por qué elegir Programación en CBTis 152?</h2>
            <p class="lead text-light">Descubre las ventajas de estudiar con nosotros</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <i class="fas fa-laptop-code feature-icon"></i>
                    <h4 class="fw-bold mb-3">Tecnología Avanzada</h4>
                    <p class="text-light">Laboratorios equipados con las últimas tecnologías y software de desarrollo profesional.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <i class="fas fa-users feature-icon"></i>
                    <h4 class="fw-bold mb-3">Comunidad Dev</h4>
                    <p class="text-light">Únete a una comunidad activa de programadores estudiantes y profesionales.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <i class="fas fa-rocket feature-icon"></i>
                    <h4 class="fw-bold mb-3">Proyectos Reales</h4>
                    <p class="text-light">Desarrolla aplicaciones y sistemas reales desde el primer semestre.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <i class="fas fa-certificate feature-icon"></i>
                    <h4 class="fw-bold mb-3">Certificaciones</h4>
                    <p class="text-light">Obtén certificaciones reconocidas internacionalmente en tu especialidad.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="container">
        <div class="stats-container">
            <div class="row">
                <div class="col-lg-3 col-md-6 stat-item">
                    <span class="stat-number">95%</span>
                    <span class="stat-label">Empleabilidad</span>
                </div>
                <div class="col-lg-3 col-md-6 stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Proyectos Desarrollados</span>
                </div>
                <div class="col-lg-3 col-md-6 stat-item">
                    <span class="stat-number">15+</span>
                    <span class="stat-label">Lenguajes de Programación</span>
                </div>
                <div class="col-lg-3 col-md-6 stat-item">
                    <span class="stat-number">100+</span>
                    <span class="stat-label">Egresados Exitosos</span>
                </div>
            </div>
        </div>
    </section> -->

    <section id="proyectos" class="container my-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-white mb-3">Tecnologías que Dominarás</h2>
            <p class="lead text-light">Stack tecnológico moderno y en demanda</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="code-block">
                    <div class="code-content">
                        <span style="color: #64748b;">// Frontend Technologies</span><br>
                        <span style="color: #f59e0b;">const</span> <span style="color: #06b6d4;">frontend</span> = {<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">languages:</span> [<span style="color: #fbbf24;">'HTML'</span>, <span style="color: #fbbf24;">'CSS'</span>, <span style="color: #fbbf24;">'JavaScript'</span>]<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">frameworks:</span> [<span style="color: #fbbf24;">'React'</span>, <span style="color: #fbbf24;">'Vue'</span>, <span style="color: #fbbf24;">'Angular'</span>]<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">tools:</span> [<span style="color: #fbbf24;">'Webpack'</span>, <span style="color: #fbbf24;">'Vite'</span>, <span style="color: #fbbf24;">'Tailwind'</span>]<br>
                        };
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="code-block">
                    <div class="code-content">
                        <span style="color: #64748b;">// Backend Technologies</span><br>
                        <span style="color: #f59e0b;">const</span> <span style="color: #06b6d4;">backend</span> = {<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">languages:</span> [<span style="color: #fbbf24;">'Python'</span>, <span style="color: #fbbf24;">'Java'</span>, <span style="color: #fbbf24;">'C#'</span>,<span style="color: #fbbf24;">'C++'</span>,<span style="color: #fbbf24;">'PHP'</span>]<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">frameworks:</span> [<span style="color: #fbbf24;">'Django'</span>, <span style="color: #fbbf24;">'Spring'</span>, <span style="color: #fbbf24;">'.NET'</span>]<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">databases:</span> [<span style="color: #fbbf24;">'MySQL'</span>, <span style="color: #fbbf24;">'MongoDB'</span>, <span style="color: #fbbf24;">'PostgreSQL'</span>]<br>
                        };
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container text-center my-5 py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold text-white mb-4">¿Listo para programar tu futuro?</h2>
                <p class="lead text-light mb-4">Únete a la próxima generación de programadores en CBTis</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#" class="interactive-btn">
                        <i class="fas fa-play me-2"></i>Ver Demo de Proyectos
                    </a>
                    <a href="#" class="interactive-btn">
                        <i class="fas fa-graduation-cap me-2"></i>Plan de Estudios
                    </a>
                    <a href="#" class="interactive-btn">
                        <i class="fas fa-comments me-2"></i>Contactar Coordinador
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="container mb-5">
        <h3 class="text-center mb-3">¿Dónde estamos ubicados?</h3>
        <div class="ratio ratio-16x9">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3677.327069189514!2d-105.79229482614119!3d22.82738582358673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x869f1d80979681a7%3A0xba8d2ae858812bee!2sCbtis%20152!5e0!3m2!1ses!2smx!4v1751597593486!5m2!1ses!2smx" 
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </iframe>
        </div>
    </section>

    <footer class="footer">
        <p>© 2025 CBTis No.152 </p>
        <p>Desarrollado para el Departamento de Programación</p>
        
        <div class="redes-sociales">
            <a href="#" target="_blank" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" target="_blank" aria-label="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" target="_blank" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" target="_blank" aria-label="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
            <p><a style="text-decoration:none" href="aviso_privacidad.php" target="_blank">Aviso de privacidad</a></p>
        </div>
    </footer> 

    <script src="js/index.js"></script>
    </body>
</html>