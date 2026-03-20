<?php
/**
 * TT4 Deboart - Функции темы
 * Версия: 3.1 (Оптимизированная структура подключения)
 */

// ==============================================
// ОСНОВНАЯ НАСТРОЙКА ТЕМЫ
// ==============================================

// Регистрация областей меню
function tt4_deboart_register_menus() {
    register_nav_menus(
        array(
            'primary' => __('Основное меню', 'tt4-deboart'),
            'footer'  => __('Меню в подвале', 'tt4-deboart'),
            'social'  => __('Социальные сети', 'tt4-deboart'),
        )
    );
}
add_action('init', 'tt4_deboart_register_menus');

// Добавление поддержки тем
function tt4_deboart_setup_theme() {
    // Поддержка логотипа
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    ));
    
    // Поддержка виджетов в блочном редакторе
    add_theme_support('widgets-block-editor');
    
    // Поддержка блоковых шаблонов
    add_theme_support('block-templates');
    
    // Поддержка выравнивания
    add_theme_support('align-wide');
    
    // Поддержка HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
add_action('after_setup_theme', 'tt4_deboart_setup_theme');

// ==============================================
// ПОДКЛЮЧЕНИЕ СТИЛЕЙ И СКРИПТОВ - ОПТИМИЗИРОВАННАЯ ВЕРСИЯ
// ==============================================

function tt4_deboart_enqueue_styles_scripts() {
    // Пути к ассетам
    $assets_path = get_stylesheet_directory() . '/assets/';
    $assets_uri = get_stylesheet_directory_uri() . '/assets/';
    
    // ========== 1. БАЗОВЫЕ СТИЛИ (ВСЕГДА ПЕРВЫМИ) ==========
    
    // Стили родительской темы
    wp_enqueue_style(
        'tt4-deboart-parent',
        get_template_directory_uri() . '/style.css'
    );
    
    // Стили дочерней темы
    wp_enqueue_style(
        'tt4-deboart-child',
        get_stylesheet_directory_uri() . '/style.css',
        array('tt4-deboart-parent'),
        filemtime(get_stylesheet_directory() . '/style.css')
    );
    
    // ========== 2. ШРИФТЫ ==========
    
    // Локальные шрифты (приоритет)
    $fonts_css = $assets_path . 'fonts/fonts.css';
    if (file_exists($fonts_css)) {
        wp_enqueue_style(
            'deboart-local-fonts',
            $assets_uri . 'fonts/fonts.css',
            array('tt4-deboart-child'),
            filemtime($fonts_css)
        );
        $fonts_dependency = 'deboart-local-fonts';
    } else {
        // Google Fonts как запасной вариант
        wp_enqueue_style(
            'deboart-google-fonts',
            'https://fonts.googleapis.com/css2?family=Spectral:wght@300;400;500;600;700&family=Manrope:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap',
            array('tt4-deboart-child'),
            null
        );
        $fonts_dependency = 'deboart-google-fonts';
    }
    
    // ========== 3. БАЗОВЫЕ СТИЛИ ТЕМЫ ==========
    
    // deboart-base.css - основные переменные и сбросы
    $base_css = $assets_path . 'css/deboart-base.css';
    if (file_exists($base_css)) {
        wp_enqueue_style(
            'deboart-base',
            $assets_uri . 'css/deboart-base.css',
            array($fonts_dependency),
            filemtime($base_css)
        );
        $base_dependency = 'deboart-base';
    } else {
        $base_dependency = $fonts_dependency;
    }
    

    
// ========== 5. ОСНОВНЫЕ КОМПОНЕНТЫ - ОДИН ФАЙЛ ==========
$components_css = $assets_path . 'css/deboart-components.css';
if (file_exists($components_css)) {
    wp_enqueue_style(
        'deboart-components',
        $assets_uri . 'css/deboart-components.css',
        array($base_dependency), // Было $header_footer_dependency
        filemtime($components_css)
    );
    $components_dependency = 'deboart-components';
} else {
    $components_dependency = $base_dependency; // Было $header_footer_dependency
}


// ========== 6. СТИЛИ АРХИВА РАБОТ ==========
// Подключаем только на страницах работ

if (is_post_type_archive('work') || is_tax('work_form') || is_tax('work_feeling') || is_tax('client') || is_tax('collaboration') || is_singular('work')) {
    
    // Архив работ - основные стили страницы
    $archive_work_css = $assets_path . 'css/pages/archive-work.css';
    if (file_exists($archive_work_css)) {
        wp_enqueue_style(
            'deboart-archive-work',
            $assets_uri . 'css/pages/archive-work.css',
            array($components_dependency),
            filemtime($archive_work_css)
        );
        $archive_dependency = 'deboart-archive-work';
    } else {
        $archive_dependency = $components_dependency;
    }
}

// ========== 7. СТИЛИ ЛАБОРАТОРИИ ==========
// Подключаем на всех страницах лаборатории: архив, отдельная запись, таксономии

if (is_post_type_archive('lab') || is_singular('lab') || is_tax('lab_tag') || is_tax('lab_category')) {
    
    // Основные стили лаборатории
    $lab_css = $assets_path . 'css/lab.css';
    if (file_exists($lab_css)) {
        wp_enqueue_style(
            'deboart-lab',
            $assets_uri . 'css/lab.css',
            array($components_dependency),
            filemtime($lab_css)
        );
    }
    
    // JavaScript для лаборатории (если нужен)
    $lab_js = $assets_path . 'js/lab.js';
    if (file_exists($lab_js)) {
        wp_enqueue_script(
            'deboart-lab',
            $assets_uri . 'js/lab.js',
            array(),
            filemtime($lab_js),
            true
        );
    }
}

// ========== 6. СТИЛИ ГЛАВНОЙ СТРАНИЦЫ ==========
// Подключаем только на главной

if (is_front_page()) {
    
    // Основной файл стилей главной страницы
    $front_page_css = $assets_path . 'css/front-page.css';
    if (file_exists($front_page_css)) {
        wp_enqueue_style(
            'deboart-front-page',
            $assets_uri . 'css/front-page.css',
            array($components_dependency), // Зависимость от компонентов
            filemtime($front_page_css)
        );
        $front_page_dependency = 'deboart-front-page';
    }
    
    // JavaScript для главной страницы (если нужен)
    $front_page_js = $assets_path . 'js/front-page.js';
    if (file_exists($front_page_js)) {
        wp_enqueue_script(
            'deboart-front-page',
            $assets_uri . 'js/front-page.js',
            array('jquery'),
            filemtime($front_page_js),
            true
        );
    }
}

    
    // ========== 6. ПОЛЬЗОВАТЕЛЬСКИЕ СТИЛИ ==========
    
    $custom_css = $assets_path . 'css/custom.css';
    if (file_exists($custom_css)) {
        wp_enqueue_style(
            'tt4-deboart-custom',
            $assets_uri . 'css/custom.css',
            array($components_dependency),
            filemtime($custom_css)
        );
        $custom_dependency = 'tt4-deboart-custom';
    } else {
        $custom_dependency = $components_dependency;
    }
    
    // ========== 7. СКРИПТЫ (ОБЩИЕ) ==========
    
    
	// Добавляем новый единый скрипт навигации
$navigation_js = $assets_path . 'js/navigation.js';
if (file_exists($navigation_js)) {
  /*  wp_enqueue_script(
        'deboart-navigation',
        $assets_uri . 'js/navigation.js',
        array(), // Без зависимостей
        filemtime($navigation_js),
        true // В футере
    );*/
}
	
	// Парадигма JS
    $paradigm_js = $assets_path . 'js/paradigm-diagram.js';
    if (file_exists($paradigm_js)) {
        wp_enqueue_script(
            'deboart-paradigm-diagram',
            $assets_uri . 'js/paradigm-diagram.js',
            array(),
            filemtime($paradigm_js),
            true
        );
    }
    

    
    // Общий кастомный JS
    $custom_js = $assets_path . 'js/custom.js';
    if (file_exists($custom_js)) {
        wp_enqueue_script(
            'tt4-deboart-custom',
            $assets_uri . 'js/custom.js',
            array('jquery'),
            filemtime($custom_js),
            true
        );
    }
    
    // ========== 8. УСЛОВНАЯ ЗАГРУЗКА ==========
	
	// ========== СКРИПТЫ ДЛЯ ГЛАВНОЙ СТРАНИЦЫ ==========
if (is_front_page()) {
    // 3D сетка для hero-секции
    $hero_grid_js = $assets_path . 'js/hero-3d-grid.js';
    if (file_exists($hero_grid_js)) {
        wp_enqueue_script(
            'deboart-hero-3d-grid',
            $assets_uri . 'js/hero-3d-grid.js',
            array(), // Нет зависимостей
            filemtime($hero_grid_js),
            true // В футере
        );
    }
}


    
    // СТРАНИЦЫ АРХИВА РАБОТ И ТАКСОНОМИИ
    if (is_post_type_archive('work') || is_tax(array('work_form', 'work_feeling'))) {
        
        // ⚠️ Filter.css - подключается ОТДЕЛЬНО (не входит в deboart-components.css)
        $filter_css = $assets_path . 'css/components/filter.css';
        if (file_exists($filter_css)) {
            wp_enqueue_style(
                'deboart-filter-css',
                $assets_uri . 'css/components/filter.css',
                array($custom_dependency),
                filemtime($filter_css)
            );
        }
        
        // AJAX фильтр
        $ajax_js = $assets_path . 'js/ajax-filter.js';
        if (file_exists($ajax_js)) {
            wp_enqueue_script(
                'deboart-ajax-filter',
                $assets_uri . 'js/ajax-filter.js',
                array('jquery'),
                filemtime($ajax_js),
                true
            );
            
            wp_localize_script('deboart-ajax-filter', 'deboart_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('filter_works_nonce')
            ));
        }
    }
    
    // ОТДЕЛЬНАЯ СТРАНИЦА РАБОТЫ
    if (is_singular('work')) {
        // Стили для отдельной работы
        $work_single_css = $assets_path . 'css/work-single.css';
        if (file_exists($work_single_css)) {
            wp_enqueue_style(
                'deboart-work-single',
                $assets_uri . 'css/work-single.css',
                array($custom_dependency),
                filemtime($work_single_css)
            );
            $work_single_dependency = 'deboart-work-single';
        } else {
            $work_single_dependency = $custom_dependency;
        }
        
        // Галерея
        $gallery_css = $assets_path . 'css/components/gallery.css';
        if (file_exists($gallery_css)) {
            wp_enqueue_style(
                'deboart-gallery-css',
                $assets_uri . 'css/components/gallery.css',
                array($work_single_dependency),
                filemtime($gallery_css)
            );
        }
        
        // Lightbox
        wp_enqueue_style(
            'lightbox-css',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css',
            array($work_single_dependency),
            '2.11.4'
        );
        
        wp_enqueue_script(
            'lightbox-js',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js',
            array('jquery'),
            '2.11.4',
            true
        );
        
        wp_add_inline_script('lightbox-js', '
            lightbox.option({
                "resizeDuration": 200,
                "wrapAround": true,
                "albumLabel": "Изображение %1 из %2",
                "fadeDuration": 300,
                "imageFadeDuration": 300
            });
        ');
        
        // Дополнительный JS для работы
        $work_js = $assets_path . 'js/work-single.js';
        if (file_exists($work_js)) {
            wp_enqueue_script(
                'deboart-work-js',
                $assets_uri . 'js/work-single.js',
                array('jquery'),
                filemtime($work_js),
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'tt4_deboart_enqueue_styles_scripts');



// ==============================================
// РЕГИСТРАЦИЯ ПАТТЕРНОВ И БЛОКОВ
// ==============================================

function tt4_deboart_register_patterns() {
    register_block_pattern_category('deboart-patterns', array(
        'label' => __('Deboart Patterns', 'tt4-deboart')
    ));
    
    register_block_style('core/heading', array(
        'name'         => 'deboart-mono',
        'label'        => __('Deboart Mono', 'tt4-deboart'),
        'inline_style' => '.is-style-deboart-mono { font-family: var(--wp--preset--font-family--ibm-plex-mono) !important; }',
    ));
}
add_action('init', 'tt4_deboart_register_patterns');

// ==============================================
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// ==============================================

function deboart_get_form_icon($slug) {
    $icons = array(
        'text'   => '📖',
        'image'  => '🖼️',
        'video'  => '🎬',
        'audio'  => '🎵',
        'web'    => '🌐',
        'object' => '✨'
    );
    return isset($icons[$slug]) ? $icons[$slug] : '🎨';
}

function deboart_get_feeling_icon($slug) {
    $icons = array(
        'tishina' => '😌',
        'energy'  => '⚡',
        'thought' => '🤔',
        'drama'   => '🎭',
        'chaos'   => '🌀',
        'memory'  => '🕰️'
    );
    return isset($icons[$slug]) ? $icons[$slug] : '💭';
}

function deboart_get_work_year($post_id) {
    if (!function_exists('pods')) {
        return '';
    }
    
    $pod = pods('work', $post_id);
    if (!$pod || !$pod->exists()) {
        return '';
    }
    
    $date = $pod->field('god');
    if (empty($date)) {
        return '';
    }
    
    $year = date('Y', strtotime($date));
    return $year;
}

// ==============================================
// AJAX ОБРАБОТЧИКИ
// ==============================================

add_action('wp_ajax_filter_works_simple', 'deboart_filter_works_simple');
add_action('wp_ajax_nopriv_filter_works_simple', 'deboart_filter_works_simple');

$ajax_handler = get_stylesheet_directory() . '/includes/ajax-handler.php';
if (file_exists($ajax_handler)) {
    require_once $ajax_handler;
}

// ==============================================
// MIME ТИПЫ И ЗАГРУЗКИ
// ==============================================

function tt4_deboart_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';
    $mimes['psd'] = 'image/vnd.adobe.photoshop';
    $mimes['doc'] = 'application/msword';
    $mimes['djv'] = 'image/vnd.djvu';
    $mimes['djvu'] = 'image/vnd.djvu';
    $mimes['png'] = 'image/png';
    return $mimes;
}
add_filter('upload_mimes', 'tt4_deboart_mime_types');

// ==============================================
// ОПТИМИЗАЦИЯ И БЕЗОПАСНОСТЬ
// ==============================================

// Удаляем версию WordPress
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
add_filter('the_generator', '__return_empty_string');

// Отключаем jQuery-migrate
add_filter('wp_default_scripts', function($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            array('jquery-migrate')
        );
    }
});

// ==============================================
// ОТЛАДКА ДЛЯ АДМИНИСТРАТОРОВ
// ==============================================

function tt4_deboart_debug_info() {
    if (current_user_can('administrator') && WP_DEBUG) {
        echo '<!-- Deboart Theme: ' . esc_html(get_stylesheet()) . ' -->' . "\n";
        echo '<!-- Version: 3.1 -->' . "\n";
        echo '<!-- Components: deboart-components.css (master) -->' . "\n";
        echo '<!-- Filter: отдельно на страницах архива -->' . "\n";
    }
}
add_action('wp_head', 'tt4_deboart_debug_info', 999);


// Добавляем колонку "Избранное" в список работ
function deboart_add_featured_column($columns) {
    $columns['featured'] = '⭐ Избранное';
    return $columns;
}
add_filter('manage_work_posts_columns', 'deboart_add_featured_column');

// Отображаем статус в колонке
function deboart_show_featured_column($column, $post_id) {
    if ($column === 'featured') {
        $featured = get_post_meta($post_id, 'featured', true);
        if ($featured) {
            echo '⭐ Да';
        } else {
            echo '—';
        }
    }
}
add_action('manage_work_posts_custom_column', 'deboart_show_featured_column', 10, 2);

// Добавляем возможность сортировки по колонке
function deboart_sortable_featured_column($columns) {
    $columns['featured'] = 'featured';
    return $columns;
}
add_filter('manage_edit-work_sortable_columns', 'deboart_sortable_featured_column');




/**
 * Кастомный Walker для меню
 * Добавляет дополнительные классы для анимации и адаптивности
 */
class Deboart_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Добавляем класс для мобильного меню
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-children';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= $indent . '<li' . $class_names . '>';
        
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Настройки хедера
 */
function deboart_header_customize_register($wp_customize) {
    // Секция для хедера
    $wp_customize->add_section('deboart_header', array(
        'title'    => __('Шапка сайта', 'tt4-deboart'),
        'priority' => 110,
    ));
    
    // Логотип хедера
    $wp_customize->add_setting('header_logo', array(
        'default'           => '/wp-content/uploads/slavadbart-logo-6-1.png',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_logo', array(
        'label'    => __('Логотип в шапке', 'tt4-deboart'),
        'section'  => 'deboart_header',
        'settings' => 'header_logo',
    )));
    
    // Текст CTA кнопки
    $wp_customize->add_setting('cta_text', array(
        'default'           => 'Сеанс связи',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('cta_text', array(
        'label'    => __('Текст кнопки', 'tt4-deboart'),
        'section'  => 'deboart_header',
        'type'     => 'text',
    ));
    
    // URL CTA кнопки
    $wp_customize->add_setting('cta_url', array(
        'default'           => '/сеанс',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('cta_url', array(
        'label'    => __('Ссылка кнопки', 'tt4-deboart'),
        'section'  => 'deboart_header',
        'type'     => 'url',
    ));
}
add_action('customize_register', 'deboart_header_customize_register');


/**
 * Fallback для основного меню
 */
function deboart_primary_menu_fallback() {
    ?>
    <ul class="primary-menu fallback-menu">
        <li class="menu-item"><a href="<?php echo esc_url(home_url('/')); ?>">DEBO</a></li>
        <li class="menu-item"><a href="<?php echo esc_url(get_post_type_archive_link('work')); ?>">ИССЛЕДОВАНИЯ</a></li>
        <li class="menu-item"><a href="<?php echo esc_url(home_url('/category/lab/')); ?>">ЛАБОРАТОРИЯ</a></li>
        <li class="menu-item"><a href="<?php echo esc_url(home_url('/protocols/')); ?>">ПРОТОКОЛЫ</a></li>
    </ul>
    <?php
}




/**
 * Настройки футера
 */
function deboart_footer_customize_register($wp_customize) {
    // Секция для футера
    $wp_customize->add_section('deboart_footer', array(
        'title'    => __('Футер', 'tt4-deboart'),
        'priority' => 120,
    ));
    
    // Логотип футера
    $wp_customize->add_setting('footer_logo', array(
        'default'           => '/wp-content/uploads/slavadbart-logo-6-1.png',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_logo', array(
        'label'    => __('Логотип в футере', 'tt4-deboart'),
        'section'  => 'deboart_footer',
        'settings' => 'footer_logo',
    )));
    
    // Текст копирайта
    $wp_customize->add_setting('copyright_text', array(
        'default'           => 'DEBOART. Все исследования защищены.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('copyright_text', array(
        'label'    => __('Текст копирайта', 'tt4-deboart'),
        'section'  => 'deboart_footer',
        'type'     => 'textarea',
    ));
}
add_action('customize_register', 'deboart_footer_customize_register');


/**
 * Получить ссылку на случайную работу
 */
function get_random_work_url() {
    $random_work = get_posts(array(
        'post_type' => 'work',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'orderby' => 'rand'
    ));
    
    if (!empty($random_work)) {
        return get_permalink($random_work[0]->ID);
    }
    
    return home_url('/works/'); // Если нет работ, ведём на архив
}


/**
 * Подключение скрипта мобильного меню (на всех устройствах)
 * Скрипт сам определит, когда нужно срабатывать
 */
function deboart_enqueue_mobile_menu_script() {
    wp_enqueue_script(
        'deboart-mobile-menu',
        get_stylesheet_directory_uri() . '/assets/js/mobile-menu.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/js/mobile-menu.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'deboart_enqueue_mobile_menu_script');


/**
 * Универсальный поиск шаблонов в папке /templates/
 * Работает для всех типов записей, страниц, архивов, таксономий
 */
function tt4_deboart_template_include($template) {
    // Получаем только имя файла из полного пути
    $template_file = basename($template);
    
    // Ищем этот файл в папке /templates/
    $new_template = locate_template('templates/' . $template_file);
    
    // Если нашли — используем его
    if ($new_template) {
        return $new_template;
    }
    
    return $template;
}
add_filter('template_include', 'tt4_deboart_template_include');


/**
 * Страница необработанных работ
 * Показывает работы без формы или содержания
 */

// Добавляем пункт меню в админку
function add_unprocessed_works_page() {
    add_menu_page(
        'Необработанные работы',
        '🔍 Необработанные',
        'edit_posts',
        'unprocessed-works',
        'render_unprocessed_works_page',
        'dashicons-warning',
        6 // Позиция после "Записи"
    );
}
add_action('admin_menu', 'add_unprocessed_works_page');

// Функция отображения страницы
function render_unprocessed_works_page() {
    // Получаем параметры сортировки и фильтрации
    $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'title';
    $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
    $filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : 'all';
    
    // Формируем запрос
    $args = array(
        'post_type' => 'work',
        'posts_per_page' => -1, // Показываем все
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order
    );
    
    // Если выбран фильтр по статусу обработки
    if ($filter_status !== 'all') {
        $tax_query = array('relation' => 'AND');
        
        if ($filter_status === 'no_form') {
            $tax_query[] = array(
                'taxonomy' => 'work_form',
                'operator' => 'NOT EXISTS'
            );
        } elseif ($filter_status === 'no_feeling') {
            $tax_query[] = array(
                'taxonomy' => 'work_feeling',
                'operator' => 'NOT EXISTS'
            );
        } elseif ($filter_status === 'no_both') {
            $tax_query[] = array(
                'taxonomy' => 'work_form',
                'operator' => 'NOT EXISTS'
            );
            $tax_query[] = array(
                'taxonomy' => 'work_feeling',
                'operator' => 'NOT EXISTS'
            );
        }
        
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }
    }
    
    $works_query = new WP_Query($args);
    $total_count = $works_query->found_posts;
    
    // Подсчет статистики
    $stats = get_unprocessed_stats();
    ?>
    
    <div class="wrap">
        <h1>🔍 Необработанные работы</h1>
        
        <!-- Статистика -->
        <div class="notice notice-info" style="margin: 20px 0; padding: 15px;">
            <h3 style="margin-top: 0;">Статистика:</h3>
            <ul style="margin-bottom: 0;">
                <li>📊 Всего работ: <strong><?php echo $stats['total']; ?></strong></li>
                <li>🎨 Без формы: <strong style="color: #d63638;"><?php echo $stats['no_form']; ?></strong></li>
                <li>💭 Без содержания: <strong style="color: #d63638;"><?php echo $stats['no_feeling']; ?></strong></li>
                <li>❌ Без формы и содержания: <strong style="color: #d63638;"><?php echo $stats['no_both']; ?></strong></li>
                <li>✅ Полностью обработано: <strong style="color: #46b450;"><?php echo $stats['complete']; ?></strong></li>
            </ul>
        </div>
        
        <!-- Фильтры -->
        <div style="margin: 20px 0;">
            <form method="get" action="">
                <input type="hidden" name="page" value="unprocessed-works">
                
                <label for="filter_status">Показать работы:</label>
                <select name="filter_status" id="filter_status" style="margin: 0 10px 0 5px;">
                    <option value="all" <?php selected($filter_status, 'all'); ?>>Все работы</option>
                    <option value="no_form" <?php selected($filter_status, 'no_form'); ?>>Только без формы</option>
                    <option value="no_feeling" <?php selected($filter_status, 'no_feeling'); ?>>Только без содержания</option>
                    <option value="no_both" <?php selected($filter_status, 'no_both'); ?>>Без формы и содержания</option>
                </select>
                
                <input type="submit" value="Применить" class="button">
                
                <?php if ($filter_status !== 'all') : ?>
                    <a href="?page=unprocessed-works" class="button" style="margin-left: 10px;">Сбросить</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Таблица работ -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="30">ID</th>
                    <th>
                        <a href="?page=unprocessed-works&orderby=title&order=<?php echo $orderby === 'title' && $order === 'ASC' ? 'DESC' : 'ASC'; ?>&filter_status=<?php echo $filter_status; ?>">
                            Название
                            <?php if ($orderby === 'title') : ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'ASC' ? 'up' : 'down'; ?>"></span>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th>
                        <a href="?page=unprocessed-works&orderby=date&order=<?php echo $orderby === 'date' && $order === 'ASC' ? 'DESC' : 'ASC'; ?>&filter_status=<?php echo $filter_status; ?>">
                            Дата
                            <?php if ($orderby === 'date') : ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'ASC' ? 'up' : 'down'; ?>"></span>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th width="150">🎨 Форма</th>
                    <th width="150">💭 Содержание</th>
                    <th width="100">Статус</th>
                    <th width="200">Действия</th>
                </tr>
            </thead>
            
            <tbody>
                <?php if ($works_query->have_posts()) : ?>
                    <?php while ($works_query->have_posts()) : $works_query->the_post(); 
                        $id = get_the_ID();
                        $form_terms = get_the_terms($id, 'work_form');
                        $feeling_terms = get_the_terms($id, 'work_feeling');
                        
                        $has_form = $form_terms && !is_wp_error($form_terms);
                        $has_feeling = $feeling_terms && !is_wp_error($feeling_terms);
                        
                        // Определяем статус
                        if (!$has_form && !$has_feeling) {
                            $status_class = 'critical';
                            $status_text = '❌ Требуется всё';
                        } elseif (!$has_form) {
                            $status_class = 'warning';
                            $status_text = '⚠️ Нет формы';
                        } elseif (!$has_feeling) {
                            $status_class = 'warning';
                            $status_text = '⚠️ Нет содержания';
                        } else {
                            $status_class = 'ok';
                            $status_text = '✅ Готово';
                        }
                        
                        // Формируем отображение таксономий
                        $form_display = array();
                        if ($has_form) {
                            foreach ($form_terms as $term) {
                                $form_display[] = $term->name;
                            }
                        } else {
                            $form_display[] = '<span style="color: #d63638;">—</span>';
                        }
                        
                        $feeling_display = array();
                        if ($has_feeling) {
                            foreach ($feeling_terms as $term) {
                                $feeling_display[] = $term->name;
                            }
                        } else {
                            $feeling_display[] = '<span style="color: #d63638;">—</span>';
                        }
                    ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td>
                                <a href="<?php echo get_edit_post_link($id); ?>" target="_blank">
                                    <?php the_title(); ?>
                                </a>
                            </td>
                            <td><?php echo get_the_date('Y-m-d'); ?></td>
                            <td><?php echo implode(', ', $form_display); ?></td>
                            <td><?php echo implode(', ', $feeling_display); ?></td>
                            <td>
                                <span style="
                                    display: inline-block;
                                    padding: 3px 8px;
                                    border-radius: 3px;
                                    <?php 
                                    if ($status_class === 'critical') echo 'background: #d63638; color: white;';
                                    elseif ($status_class === 'warning') echo 'background: #f0ad4e; color: white;';
                                    else echo 'background: #46b450; color: white;';
                                    ?>
                                ">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo get_edit_post_link($id); ?>" class="button button-small" target="_blank">
                                    ✏️ Редактировать
                                </a>
                                <a href="<?php echo get_permalink($id); ?>" class="button button-small" target="_blank">
                                    👁️ Просмотр
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">
                            🎉 Все работы обработаны!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
            <tfoot>
                <tr>
                    <th colspan="7" style="text-align: right;">
                        Показано: <?php echo $works_query->post_count; ?> из <?php echo $total_count; ?>
                    </th>
                </tr>
            </tfoot>
        </table>
        
        <?php wp_reset_postdata(); ?>
    </div>
    
    <style>
    .wp-list-table th a {
        text-decoration: none;
        color: inherit;
    }
    .wp-list-table th .dashicons {
        font-size: 16px;
        width: 16px;
        height: 16px;
        margin-left: 4px;
    }
    </style>
    
    <?php
}

// Функция для подсчета статистики
function get_unprocessed_stats() {
    $stats = array(
        'total' => 0,
        'no_form' => 0,
        'no_feeling' => 0,
        'no_both' => 0,
        'complete' => 0
    );
    
    $all_works = get_posts(array(
        'post_type' => 'work',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids'
    ));
    
    $stats['total'] = count($all_works);
    
    foreach ($all_works as $work_id) {
        $form_terms = get_the_terms($work_id, 'work_form');
        $feeling_terms = get_the_terms($work_id, 'work_feeling');
        
        $has_form = $form_terms && !is_wp_error($form_terms);
        $has_feeling = $feeling_terms && !is_wp_error($feeling_terms);
        
        if (!$has_form) {
            $stats['no_form']++;
        }
        if (!$has_feeling) {
            $stats['no_feeling']++;
        }
        if (!$has_form && !$has_feeling) {
            $stats['no_both']++;
        }
        if ($has_form && $has_feeling) {
            $stats['complete']++;
        }
    }
    
    return $stats;
}

/**
 * конец Страницы  необработанных работ
 */

 // Автоматическая очистка кэша WP Super Cache при обновлении работ
function deboart_clear_cache_on_work_update($post_id) {
    if (get_post_type($post_id) === 'work') {
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
    }
}
add_action('save_post_work', 'deboart_clear_cache_on_work_update');


// Подключаем ротатор заголовков только на главной странице
add_action('wp_enqueue_scripts', function() {
    if (is_front_page()) {
        wp_enqueue_script('hero-rotator', get_stylesheet_directory_uri() . '/assets/js/hero-rotator.js', array(), '1.0', true);
    }
});

add_action('wp_enqueue_scripts', function() {
    if (is_page_template('page-manifest.php')) {
        wp_enqueue_style('manifest-styles', get_stylesheet_directory_uri() . '/assets/css/manifest.css', array(), '1.0');
    }
});