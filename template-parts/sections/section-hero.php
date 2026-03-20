<?php
/**
 * Hero секция главной страницы
 * 
 * Заголовок динамически подставляется через JS
 */
$hero_subtitle = get_theme_mod('hero_subtitle', 'Прокрутите, чтобы начать исследование');
?>

<section class="front-section section-hero" id="deboart-hero-section">
    <div class="section-container">
        <!-- Динамический заголовок -->
        <h1 class="hero-title" id="rotating-title"></h1>
        
        <?php if ($hero_subtitle) : ?>
            <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>
        
        <!-- Интерактивная стрелка для скролла -->
        <div class="scroll-indicator">
            <a href="#deboart-method-section" class="scroll-arrow" aria-label="Перейти к разделу 'Метод'">
                <svg width="24" height="40" viewBox="0 0 24 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 38L12 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M2 28L12 38L22 28" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<?php
// JavaScript для плавного скролла и анимаций
// В идеале вынести в отдельный файл assets/js/front-page.js
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroSection = document.querySelector('.section-hero');
    const scrollArrow = document.querySelector('.scroll-arrow');
    const scrollText = document.querySelector('.hero-subtitle');
    
    if (!heroSection) return;
    
    // Функция плавного скролла
    function smoothScrollTo(targetElement) {
        if (!targetElement) return;
        
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
    
    // Функция скролла к секции "Метод"
    function scrollToMethodSection() {
        // Пробуем найти секцию по ID или классу
        const methodSection = document.querySelector('#deboart-method-section, .section-method');
        
        if (methodSection) {
            smoothScrollTo(methodSection);
            
            // Визуальная обратная связь
            if (scrollArrow) {
                scrollArrow.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    scrollArrow.style.transform = 'scale(1)';
                }, 150);
            }
        } else {
            // Если секция не найдена, скроллим на одну высоту экрана
            window.scrollBy({
                top: window.innerHeight,
                behavior: 'smooth'
            });
        }
    }
    
    // Клик на стрелку
    if (scrollArrow) {
        scrollArrow.addEventListener('click', function(e) {
            e.preventDefault();
            scrollToMethodSection();
        });
    }
    
    // Клик на текст подзаголовка
    if (scrollText) {
        scrollText.style.cursor = 'pointer';
        scrollText.addEventListener('click', scrollToMethodSection);
    }
    
    // Параллакс эффект для текста (опционально)
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                const scrollY = window.scrollY;
                const scrollIndicator = document.querySelector('.scroll-indicator');
                
                // Прячем стрелку при скролле
                if (scrollIndicator && scrollY > 100) {
                    scrollIndicator.style.opacity = Math.max(0, 1 - scrollY / 300);
                } else if (scrollIndicator) {
                    scrollIndicator.style.opacity = 1;
                }
                
                ticking = false;
            });
            
            ticking = true;
        }
    });
    
    // Анимация пульсации стрелки
    function animateScrollArrow() {
        const arrow = document.querySelector('.scroll-arrow');
        if (arrow) {
            arrow.style.animation = 'scrollBounce 2s infinite';
        }
    }
    
    // Запускаем анимацию после загрузки
    setTimeout(animateScrollArrow, 1000);
});
</script>