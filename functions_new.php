<?php
/**
 * TT4 Deboart - Функции темы
 */

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
}
add_action('after_setup_theme', 'tt4_deboart_setup_theme');

// ==============================================
// ПОДКЛЮЧЕНИЕ СТИЛЕЙ И СКРИПТОВ (ОБНОВЛЕННАЯ ВЕРСИЯ)
// ==============================================

function tt4_deboart_enqueue_styles_scripts() {
    // Пути к ассетам
    $assets_path = get_stylesheet_directory() . '/assets/';
    $assets_uri = get_stylesheet_directory_uri() . '/assets/';
    
    // ========== СТИЛИ ==========
    
    // 1. Стили родительской темы (Twenty Twenty-Four)
    wp_enqueue_style(
        'tt4-deboart-parent',
        get_template_directory_uri() . '/style.css'
    );
    
    // 2. Стили дочерней темы (style.css)
    wp_enqueue_style(
        'tt4-deboart-child',
        get_stylesheet_directory_uri() . '/style.css',
        array('tt4-deboart-parent'),
        filemtime(get_stylesheet_directory() . '/style.css')
    );
    
    // 3. ЛОКАЛЬНЫЕ ШРИФТЫ (приоритет: локальные → Google Fonts)
    $fonts_css = $assets_path . 'fonts/fonts.css';
    if (file_exists($fonts_css)) {
        // Подключаем локальные шрифты
        wp_enqueue_style(
            'deboart-local-fonts',
            $assets_uri . 'fonts/fonts.css',
            array('tt4-deboart-child'),
            filemtime($fonts_css)
        );
        $fonts_dependency = 'deboart-local-fonts';
    } else {
        // Fallback на Google Fonts если локальные не найдены
        wp_enqueue_style(
            'deboart-google-fonts',
            'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap',
            array('tt4-deboart-child'),
            null
        );
        $fonts_dependency = 'deboart-google-fonts';
    }
    
    // 4. Базовые стили темы (deboart-base.css)
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
    
    // 5. ГЛАВНЫЙ файл компонентов (deboart-components.css)
    $components_css = $assets_path . 'css/deboart-components.css';
    if (file_exists($components_css)) {
        wp_enqueue_style(
            'deboart-components',
            $assets_uri . 'css/deboart-components.css',
            array($base_dependency),
            filemtime($components_css)
        );
    }
    
    // 6. Дополнительные CSS (если есть)
    $custom_css = $assets_path . 'css/custom.css';
    if (file_exists($custom_css)) {
        wp_enqueue_style(
            'tt4-deboart-custom',
            $assets_uri . 'css/custom.css',
            array('deboart-components'),
            filemtime($custom_css)
        );
    }
    
    // 7. Отдельные компоненты (если нужно подключать напрямую)
    $paradigm_css = $assets_path . 'css/components/paradigm-diagram.css';
    if (file_exists($paradigm_css)) {
        wp_enqueue_style(
            'deboart-paradigm-css',
            $assets_uri . 'css/components/paradigm-diagram.css',
            array('deboart-components'),
            filemtime($paradigm_css)
        );
    }
    
    // 8. Стили для архива работ
    if (is_post_type_archive('work') || is_tax(array('work_form', 'work_feeling', 'work_year'))) {
        $archive_css = $assets_path . 'css/archive-work.css';
        if (file_exists($archive_css)) {
            wp_enqueue_style(
                'deboart-archive-work',
                $assets_uri . 'css/archive-work.css',
                array('deboart-components'),
                filemtime($archive_css)
            );
        }
    }
    
    // ========== СКРИПТЫ ==========
    
    // 1. JS для парадигмы (главный интерактивный компонент)
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
    
    // 2. AJAX фильтр для страниц работ (подключаем только там, где нужно)
    if (is_post_type_archive('work') || is_tax(array('work_form', 'work_feeling', 'work_year'))) {
        // jQuery уже входит в WordPress
        wp_enqueue_script('jquery');
        
        // AJAX фильтр для архива работ
        wp_enqueue_script(
            'deboart-archive-filter',
            $assets_uri . 'js/ajax-filter.js',
            array('jquery'),
            filemtime($assets_path . 'js/ajax-filter.js'),
            true
        );
        
        // Локализация для AJAX
        wp_localize_script('deboart-archive-filter', 'deboart_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('filter_works_nonce')
        ));
        
        // Стили для фильтра
        $filter_css = $assets_path . 'css/filter.css';
        if (file_exists($filter_css)) {
            wp_enqueue_style(
                'deboart-filter-css',
                $assets_uri . 'css/filter.css',
                array('deboart-components'),
                filemtime($filter_css)
            );
        }
    }
    
    // 3. Общие скрипты темы
    $custom_js = $assets_path . 'js/custom.js';
    if (file_exists($custom_js)) {
        wp_enqueue_script(
            'tt4-deboart-custom',
            $assets_uri . 'js/custom.js',
            array(),
            filemtime($custom_js),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'tt4_deboart_enqueue_styles_scripts');

// ==============================================
// ОТЛАДКА И ИНСТРУМЕНТЫ РАЗРАБОТЧИКА
// ==============================================

// Показывает отладочную информацию в <head> для администраторов
function tt4_deboart_debug_info() {
    if (current_user_can('administrator') && WP_DEBUG) {
        $assets_path = get_stylesheet_directory() . '/assets/';
        
        echo '<!-- === DEBOART DEBUG INFO === -->' . "\n";
        echo '<!-- Theme Path: ' . esc_html(get_stylesheet_directory()) . ' -->' . "\n";
        echo '<!-- Theme URI: ' . esc_url(get_stylesheet_directory_uri()) . ' -->' . "\n\n";
        
        // Проверяем основные файлы
        $critical_files = array(
            'Fonts CSS'        => '/assets/fonts/fonts.css',
            'Base CSS'         => '/assets/css/deboart-base.css',
            'Components CSS'   => '/assets/css/deboart-components.css',
            'Paradigm CSS'     => '/assets/css/components/paradigm-diagram.css',
            'Paradigm JS'      => '/assets/js/paradigm-diagram.js',
        );
        
        foreach ($critical_files as $name => $file) {
            $full_path = get_stylesheet_directory() . $file;
            $exists = file_exists($full_path);
            echo '<!-- ' . esc_html($name) . ': ' . ($exists ? '✅ EXISTS' : '❌ NOT FOUND') . ' -->' . "\n";
            
            if ($exists) {
                echo '<!--     Path: ' . esc_html($full_path) . ' -->' . "\n";
                echo '<!--     Size: ' . esc_html(filesize($full_path) . ' bytes') . ' -->' . "\n";
            }
        }
        
        echo '<!-- === END DEBUG === -->' . "\n\n";
    }
}
add_action('wp_head', 'tt4_deboart_debug_info', 1);

// ==============================================
// РЕГИСТРАЦИЯ ПАТТЕРНОВ И БЛОКОВ
// ==============================================

// Регистрируем категорию паттернов
function tt4_deboart_register_patterns() {
    register_block_pattern_category('deboart-patterns', array(
        'label' => __('Deboart Patterns', 'tt4-deboart')
    ));
    
    // Регистрируем стили блоков если нужно
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

// Функция для получения иконок форм
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

// Функция для получения иконок содержания
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

// ==============================================
// AJAX ИНТЕГРАЦИЯ
// ==============================================

// Подключение AJAX обработчиков
add_action('wp_ajax_filter_works_simple', 'deboart_filter_works_simple');
add_action('wp_ajax_nopriv_filter_works_simple', 'deboart_filter_works_simple');

// НОВЫЙ AJAX обработчик для улучшенной фильтрации
add_action('wp_ajax_filter_works', 'deboart_filter_works');
add_action('wp_ajax_nopriv_filter_works', 'deboart_filter_works');

function deboart_filter_works() {
    // Проверка nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'filter_works_nonce')) {
        wp_send_json_error('Ошибка безопасности');
    }
    
    $args = array(
        'post_type' => 'work',
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
        'orderby' => 'meta_value_num',
        'meta_key' => 'work_year',
        'order' => 'DESC'
    );
    
    // Фильтр по поиску
    if (!empty($_POST['search'])) {
        $args['s'] = sanitize_text_field($_POST['search']);
    }
    
    // Фильтр по таксономиям
    $tax_query = array('relation' => 'AND');
    
    if (!empty($_POST['form'])) {
        $tax_query[] = array(
            'taxonomy' => 'work_form',
            'field' => 'slug',
            'terms' => array_map('sanitize_text_field', (array)$_POST['form'])
        );
    }
    
    if (!empty($_POST['feeling'])) {
        $tax_query[] = array(
            'taxonomy' => 'work_feeling',
            'field' => 'slug',
            'terms' => array_map('sanitize_text_field', (array)$_POST['feeling'])
        );
    }
    
    if (!empty($_POST['year'])) {
        // Проверяем, есть ли таксономия work_year
        if (taxonomy_exists('work_year')) {
            $tax_query[] = array(
                'taxonomy' => 'work_year',
                'field' => 'slug',
                'terms' => sanitize_text_field($_POST['year'])
            );
        } else {
            // Если нет таксономии, фильтруем по метаполю
            $args['meta_query'] = array(
                array(
                    'key' => 'work_year',
                    'value' => sanitize_text_field($_POST['year']),
                    'compare' => '='
                )
            );
        }
    }
    
    if (!empty($_POST['client'])) {
        $tax_query[] = array(
            'taxonomy' => 'client',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['client'])
        );
    }
    
    if (!empty($_POST['collaboration'])) {
        $tax_query[] = array(
            'taxonomy' => 'collaboration',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['collaboration'])
        );
    }
    
    if (!empty($_POST['category'])) {
        $tax_query[] = array(
            'taxonomy' => 'work_category',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['category'])
        );
    }
    
    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }
    
    // Выполняем запрос
    $works_query = new WP_Query($args);
    
    // Сохраняем глобальный запрос для пагинации
    global $wp_query;
    $temp_query = $wp_query;
    $wp_query = $works_query;
    
    // Получаем HTML
    ob_start();
    get_template_part('template-parts/works-grid');
    $html = ob_get_clean();
    
    // Восстанавливаем глобальный запрос
    $wp_query = $temp_query;
    
    // Возвращаем результат
    wp_send_json_success(array(
        'html' => $html,
        'count' => $works_query->found_posts,
        'max_num_pages' => $works_query->max_num_pages
    ));
}

// Подключение основного AJAX обработчика из внешнего файла
$ajax_handler = get_stylesheet_directory() . '/includes/ajax-handler.php';
if (file_exists($ajax_handler)) {
    require_once $ajax_handler;
}

// ==============================================
// ДОПОЛНИТЕЛЬНЫЕ ФИЛЬТРЫ И НАСТРОЙКИ
// ==============================================

// Отключаем эмодзи если не нужны
// remove_action('wp_head', 'print_emoji_detection_script', 7);
// remove_action('wp_print_styles', 'print_emoji_styles');

// Оптимизация загрузки jQuery (если не используем старые плагины)
if (!is_admin()) {
    add_filter('wp_default_scripts', function(&$scripts) {
        if (isset($scripts->registered['jquery'])) {
            $scripts->registered['jquery']->deps = array_diff(
                $scripts->registered['jquery']->deps,
                array('jquery-migrate')
            );
        }
    });
}

// Добавляем поддержку SVG загрузки
function tt4_deboart_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'tt4_deboart_mime_types');

// ==============================================
// БЕЗОПАСНОСТЬ И ОПТИМИЗАЦИЯ
// ==============================================

// Убираем версию WordPress из мета-тегов
function tt4_deboart_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'tt4_deboart_remove_wp_version');

// Убираем ненужные теги из <head>
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');