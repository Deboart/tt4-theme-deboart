<?php
/**
 * Template Name: Кастомная главная страница
 * Description: Главная страница с динамическими секциями
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';
?>

<main id="primary" class="site-main front-page">
    
    <!-- Секция 1: ЗАСТАВКА (Hero) -->
    <?php get_template_part('template-parts/sections/section', 'hero'); ?>
    
    <!-- Секция 2: МЕТОД + МИНИАТЮРЫ -->
    <?php get_template_part('template-parts/sections/section', 'method'); ?>
    
    <!-- Секция 3: ПАРАДИГМА «ФОРМА → СОДЕРЖАНИЕ» -->
    <?php get_template_part('template-parts/sections/section', 'paradigm'); ?>
    
    <!-- Секция 4: ИССЛЕДОВАНИЯ (3 ключевые работы) -->
    <?php get_template_part('template-parts/sections/section', 'research'); ?>

    <!-- Добавляем блок "Сейчас в работе" -->
    <?php get_template_part('template-parts/sections/section', 'current-projects'); ?>
    
    <!-- Секция 5: ЛАБОРАТОРИЯ (последние записи) -->
    <?php get_template_part('template-parts/sections/section', 'lab'); ?>
    
    <!-- Секция 6: КОНТАКТЫ / ВОПРОС -->
    <?php get_template_part('template-parts/sections/section', 'contact'); ?>
    
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>