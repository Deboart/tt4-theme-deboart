<?php
/**
 * Title: Deboart Hero Заставка
 * Slug: deboart/hero-section
 * Categories: featured
 * Description: Главная заставка сайта Deboart с фирменным сообщением
 */
?>

<!-- wp:group {"align":"full","className":"deboart-hero-section","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull deboart-hero-section">
    
    <div class="wp-block-group__inner-container">
        
        <!-- Главный заголовок - ОБНОВЛЕН -->
        <!-- wp:heading {"textAlign":"center","level":1,"className":"hero-heading","fontFamily":"spectral","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|medium"}}}} -->
        <h1 class="wp-block-heading has-text-align-center hero-heading has-spectral-font-family" style="margin-bottom:var(--wp--preset--spacing--medium);">
            ВОПРОС К ФОРМЕ.<br>
            <span style="color:var(--wp--preset--color--accent-blue);">ОТВЕТ — В СОДЕРЖАНИИ.</span>
        </h1>
        <!-- /wp:heading -->

        <!-- Подзаголовок - ОБНОВЛЕН -->
        <!-- wp:paragraph {"align":"center","className":"hero-subtitle","fontFamily":"manrope"} -->
        <p class="has-text-align-center hero-subtitle has-manrope-font-family">
            Прокрутите, чтобы начать исследование
        </p>
        <!-- /wp:paragraph -->

        <!-- Интерактивная стрелка для скролла -->
        <!-- wp:group {"align":"center","className":"scroll-indicator","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","orientation":"vertical"}} -->
        <div class="wp-block-group aligncenter scroll-indicator">
            
            <!-- Анимированная стрелка SVG -->
            <a href="#deboart-method-section" class="scroll-arrow" aria-label="Перейти к разделу 'Метод'" data-target=".deboart-method-section">
                <svg width="24" height="40" viewBox="0 0 24 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 38L12 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M2 28L12 38L22 28" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            
        </div>
        <!-- /wp:group -->
        
    </div>
    
</div>
<!-- /wp:group -->

<!-- JavaScript для плавного скролла -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция плавного скролла
    function smoothScrollTo(targetElement) {
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
    
    // Обработчик для стрелки
    const scrollArrow = document.querySelector('.scroll-arrow');
    const scrollText = document.querySelector('.hero-subtitle');
    
    // Функция скролла к секции "Метод"
    function scrollToMethodSection() {
        const methodSection = document.querySelector('.deboart-method-section');
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
        
        // Hover эффекты для стрелки
        scrollArrow.addEventListener('mouseenter', function() {
            this.style.color = 'var(--wp--preset--color--accent-blue)';
        });
        
        scrollArrow.addEventListener('mouseleave', function() {
            this.style.color = '';
        });
    }
    
    // Клик на текст подзаголовка
    if (scrollText) {
        scrollText.style.cursor = 'pointer';
        scrollText.addEventListener('click', scrollToMethodSection);
        
        // Hover эффекты для текста
        scrollText.addEventListener('mouseenter', function() {
            this.style.color = 'var(--wp--preset--color--accent-blue)';
        });
        
        scrollText.addEventListener('mouseleave', function() {
            this.style.color = 'inherit';
        });
    }
    
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

// Параллакс эффект для текста
window.addEventListener('scroll', function() {
    const scrollY = window.scrollY;
    const heroTitle = document.querySelector('.hero-title');
    const scrollIndicator = document.querySelector('.hero-scroll-indicator');
    
    if (heroTitle) {
        document.documentElement.style.setProperty('--scroll-y', scrollY);
    }
    
    // Прячем стрелку при скролле
    if (scrollIndicator && scrollY > 100) {
        scrollIndicator.style.opacity = Math.max(0, 1 - scrollY / 300);
    }
});
</script>