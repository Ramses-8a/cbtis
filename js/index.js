document.addEventListener('DOMContentLoaded', function() {
    // 1. Typing animation para el encabezado
    const typingElement = document.querySelector('.typing-animation');
    const text = 'Especialidad en Programación';
    let i = 0;
    
    // Asegurarse de que el texto esté vacío al inicio para la animación
    if (typingElement) { // Verificar si el elemento existe
        typingElement.textContent = ''; 
        setTimeout(typeWriter, 1000); // Iniciar la animación después de 1 segundo
    }

    function typeWriter() {
        if (i < text.length) {
            typingElement.textContent += text.charAt(i);
            i++;
            setTimeout(typeWriter, 100);
        } else {
            // Opcional: Remover el cursor parpadeante después de terminar
            typingElement.style.borderRight = 'none'; 
        }
    }

    // 2. Smooth scrolling para enlaces de ancla internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');

            // Importante: No prevenir el default si el href es solo '#' o '#!'
            // Esto permite que los dropdowns de Bootstrap funcionen correctamente
            if (href === '#' || href === '#!') {
                return; 
            }

            // Si es un enlace de ancla real (ej. #explorar, #proyectos), prevenir el default y hacer smooth scroll
            e.preventDefault(); 
            const target = document.querySelector(href); // Usar el href directamente para el selector
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // 3. Counter animation para las estadísticas
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/\D/g, ''));
            const suffix = counter.textContent.replace(/\d/g, '');
            let current = 0;
            const increment = target / 100; // Dividir en 100 pasos para la animación
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current) + suffix; // Redondear hacia arriba
                    requestAnimationFrame(updateCounter); // Usar requestAnimationFrame para un scroll más suave
                } else {
                    counter.textContent = target + suffix; // Asegurarse de que el número final sea exacto
                }
            };
            
            updateCounter();
        });
    }

    // Intersection Observer para activar las animaciones al entrar en vista
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('stats-container')) {
                    animateCounters();
                    observer.unobserve(entry.target); // Dejar de observar una vez que la animación se ha disparado
                }
            }
        });
    }, { threshold: 0.5 }); // Opciones del observador: 50% del elemento visible

    // Observar el contenedor de estadísticas
    document.querySelectorAll('.stats-container').forEach(el => {
        observer.observe(el);
    });

    // 4. Interactive hover effects para las feature-cards
    document.querySelectorAll('.feature-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) rotateX(5deg)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) rotateX(0)';
        });
    });
});