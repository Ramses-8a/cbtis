<?php 
 include_once('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->
    <link rel="stylesheet" href="css/index.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBTis NO.152</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-animated">
    <header class="hero-header">
        <div class="hero-content">
            <div class="container">
                <h1 class="display-4 fw-bold mb-3">
                   <i class="fas fa-code text-white"></i>  <!-- CBTis No.152 -->
                </h1>
                <div class="typing-animation code-font display-6 mb-4">
                    Especialidad en Programación
                </div>
                <p class="lead mb-4">Formando los desarrolladores del futuro con tecnología de vanguardia</p>
                <a href="#explorar" class="interactive-btn me-3">
                    <i class="fas fa-rocket me-2"></i>Explorar Especialidad
                </a>
                <a href="mostrar_proyectos.php" class="interactive-btn">
                    <i class="fas fa-code-branch me-2"></i>Ver Proyectos
                </a>
            </div>
        </div>
    </header>

    <!-- Bloque de Bienvenida -->
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

    <!-- Segunda sección -->
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
            
            <!-- <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <i class="fas fa-brain feature-icon"></i>
                    <h4 class="fw-bold mb-3">IA & Machine Learning</h4>
                    <p class="text-light">Especialízate en las tecnologías más demandadas del mercado actual.</p>
                </div>
            </div> -->
            
            <!-- <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <i class="fas fa-mobile-alt feature-icon"></i>
                    <h4 class="fw-bold mb-3">Desarrollo Móvil</h4>
                    <p class="text-light">Crea apps innovadoras para Android e iOS con tecnologías multiplataforma.</p>
                </div>
            </div> -->
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <i class="fas fa-certificate feature-icon"></i>
                    <h4 class="fw-bold mb-3">Certificaciones</h4>
                    <p class="text-light">Obtén certificaciones reconocidas internacionalmente en tu especialidad.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadisticas -->
    <section class="container">
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
    </section>

    <!-- Tech Stack Section -->
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
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">languages:</span> [<span style="color: #fbbf24;">'HTML'</span>, <span style="color: #fbbf24;">'CSS'</span>, <span style="color: #fbbf24;">'JavaScript'</span>],<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">frameworks:</span> [<span style="color: #fbbf24;">'React'</span>, <span style="color: #fbbf24;">'Vue'</span>, <span style="color: #fbbf24;">'Angular'</span>],<br>
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
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">languages:</span> [<span style="color: #fbbf24;">'Python'</span>, <span style="color: #fbbf24;">'Java'</span>, <span style="color: #fbbf24;">'C#'</span>],<span style="color: #fbbf24;">'C++'</span>],<span style="color: #fbbf24;">'PHP'</span>]<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">frameworks:</span> [<span style="color: #fbbf24;">'Django'</span>, <span style="color: #fbbf24;">'Spring'</span>, <span style="color: #fbbf24;">'.NET'</span>],<br>
                        &nbsp;&nbsp;<span style="color: #f3f4f6;">databases:</span> [<span style="color: #fbbf24;">'MySQL'</span>, <span style="color: #fbbf24;">'MongoDB'</span>, <span style="color: #fbbf24;">'PostgreSQL'</span>]<br>
                        };
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="container text-center my-5 py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold text-white mb-4">¿Listo para programar tu futuro?</h2>
                <p class="lead text-light mb-4">Únete a la próxima generación de desarrolladores en CBTis</p>
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

     <!-- Footer -->
    <footer class="bg-dark text-center py-4 border-top border-secondary">
        <div class="container">
            <p class="mb-0 text-light">
                <i class="fas fa-heart text-danger"></i> 
                Hecho con código y pasión por estudiantes CBTis | 
                <span class="code-font">console.log("¡Programa tu futuro!");</span>
            </p>
        </div>
    </footer> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<!-- Mapa -->
<section class="container mb-5">
    <h3 class="text-center mb-3">¿Dónde estamos ubicados?</h3>
    <div class="ratio ratio-16x9">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2200.423873219167!2d-105.78967252589628!3d22.82728358214901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x869f1d80979681a7%3A0xba8d2ae858812bee!2sCbtis%20152!5e0!3m2!1ses!2smx!4v1750823746978!5m2!1ses!2smx" 
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
      </iframe>
        <!-- <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3733.587310015096!2d-103.34960978450467!3d20.676719404377576!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8428adbf02e85d2b%3A0x85e0f162cbdf6cb0!2sGuadalajara%2C%20Jalisco!5e0!3m2!1ses!2smx!4v1625134121380!5m2!1ses!2smx"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe> -->
    </div>
</section>

<!-- Footer -->
<footer class="footer">
  <p>© 2025 CBTis No.152 </p>
  <p>Desarrollado para el Departamento de Programación</p>
  
  <div class="redes-sociales">
    <a href="" target="_blank" aria-label="Facebook">
      <i class="fab fa-facebook-f"></i>
    </a>
    <a href="" target="_blank" aria-label="Twitter">
      <i class="fab fa-twitter"></i>
    </a>
    <a href="" target="_blank" aria-label="Instagram">
      <i class="fab fa-instagram"></i>
    </a>
    <a href="" target="_blank" aria-label="YouTube">
      <i class="fab fa-youtube"></i>
    </a>
  </div>
</footer>

</body>
</html>

<style>
  .footer {
  background-color: #f8f8f8;
  color: #9d0707;
  text-align: center;
  padding: 20px 10px;
  font-size: 0.9rem;
  border-top: 2px solid #9d0707;
  margin-top: 40px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.footer p {
  margin: 5px 0;
}

.redes-sociales {
  margin-top: 10px;
}

.redes-sociales a {
  color: #9d0707;
  margin: 0 10px;
  font-size: 1.2rem;
  transition: transform 0.2s ease, color 0.2s ease;
}

.redes-sociales a:hover {
  color: #d10000;
  transform: scale(1.2);
}

</style>

<script>
    // Typing animation
    document.addEventListener('DOMContentLoaded', function() {
        const typingElement = document.querySelector('.typing-animation');
        const text = 'Especialidad en Programación';
        let i = 0;
        
        typingElement.textContent = '';
        
        function typeWriter() {
            if (i < text.length) {
                typingElement.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        }
        
        setTimeout(typeWriter, 1000);
    });

    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Counter animation for stats
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/\D/g, ''));
            const suffix = counter.textContent.replace(/\d/g, '');
            let current = 0;
            const increment = target / 100;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current) + suffix;
                    setTimeout(updateCounter, 30);
                } else {
                    counter.textContent = target + suffix;
                }
            };
            
            updateCounter();
        });
    }

    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('stats-container')) {
                    animateCounters();
                }
            }
        });
    });

    document.querySelectorAll('.stats-container').forEach(el => {
        observer.observe(el);
    });

    // Interactive hover effects
    document.querySelectorAll('.feature-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) rotateX(5deg)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) rotateX(0)';
        });
    });
</script>