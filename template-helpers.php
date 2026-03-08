<?php
// template-helpers.php

/**
 * Универсальная функция для подключения header
 */
function deboart_get_header_modern($name = null) {
    if (function_exists('wp_is_block_theme') && wp_is_block_theme()) {
        // Подключаем скрипты для меню
        add_action('wp_footer', 'deboart_menu_scripts');
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
            <?php wp_head(); ?>
        </head>
        <body <?php body_class('deboart-body'); ?>>
        <?php wp_body_open(); ?>
        
        <div id="page" class="site deboart-site">
        <?php
        
        $slug = $name ? 'header-' . $name : 'header';
        block_template_part($slug);
        
    } else {
        if ($name) {
            get_header($name);
        } else {
            get_header();
        }
    }
}

/**
 * Подключение скриптов для меню
 */
function deboart_menu_scripts() {
    ?>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/header-footer.js"></script>
    <?php
}

/**
 * Универсальная функция для подключения footer
 */
function deboart_get_footer_modern($name = null) {
    if (function_exists('wp_is_block_theme') && wp_is_block_theme()) {
        $slug = $name ? 'footer-' . $name : 'footer';
        block_template_part($slug);
        ?>
        </div><!-- #page -->
        <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    } else {
        if ($name) {
            get_footer($name);
        } else {
            get_footer();
        }
    }
}