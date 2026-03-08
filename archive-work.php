<?php
/**
 * Template Name: Archive Works
 * Template Post Type: work
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';

// Подключаем функции для иконок
if (!function_exists('deboart_get_form_icon')) {
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
}

if (!function_exists('deboart_get_feeling_icon')) {
    function deboart_get_feeling_icon($slug) {
        // Приводим к нижнему регистру для сравнения
        $slug_lower = strtolower($slug);
        
        // Основные иконки по английским slug'ам
        $icons = array(
            'tishina'   => '😌',
            'energy'    => '⚡',
            'thought'   => '🤔',
            'drama'     => '🎭',
            'chaos'     => '🌀',
            'memory'    => '🕰️',
            
            // Добавляем русские варианты на всякий случай
            'тишина'    => '😌',
            'энергия'   => '⚡',
            'мысль'     => '🤔',
            'драма'     => '🎭',
            'хаос'      => '🌀',
            'память'    => '🕰️'
        );
        
        // Проверяем точное совпадение
        if (isset($icons[$slug_lower])) {
            return $icons[$slug_lower];
        }
        
        // Проверяем частичное совпадение (например, если в slug есть слово)
        foreach ($icons as $key => $icon) {
            if (strpos($slug_lower, $key) !== false) {
                return $icon;
            }
        }
        
        // Иконка по умолчанию
        return '💭';
    }
}

// Получаем текущие параметры фильтрации
$current_forms = isset($_GET['form']) ? (array)$_GET['form'] : array();
$current_feelings = isset($_GET['feeling']) ? (array)$_GET['feeling'] : array();
$current_client = isset($_GET['client']) ? sanitize_text_field($_GET['client']) : '';
$current_collaboration = isset($_GET['collaboration']) ? sanitize_text_field($_GET['collaboration']) : '';
$current_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$current_search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// Получаем глобальный запрос
global $wp_query;
$total_works = $wp_query->found_posts;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = get_option('posts_per_page');
$start = (($paged - 1) * $posts_per_page) + 1;
$end = min($paged * $posts_per_page, $total_works);
?>

<main id="primary" class="site-main archive-work">
 <!-- Весь контент теперь внутри lab-archive__content -->
    <div class="work-archive__content">

    <div class="archive-header">
        <h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
        
        <!-- Статистика работ -->
        <div class="archive-stats">
            <p class="archive-description">
                <span class="stats-count">🕰️ Показано <?php echo $start; ?>—<?php echo $end; ?> из <?php echo $total_works; ?> работ</span>
                <span class="stats-sort">Сортировка: по году создания (новые → старые)</span>
            </p>
        </div>
    </div>

    <!-- ФИЛЬТРЫ С AJAX -->
    <div class="archive-filters-container">
        <form id="works-filter-form" class="works-filter-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('work')); ?>">
            
            <!-- ВЕРХНЯЯ СТРОКА: поиск + основные фильтры -->
   
               <!-- ВЕРХНЯЯ СТРОКА: поиск + основные фильтры в один ряд -->
            <div class="filters-top-row">
                <!-- Поиск - фиксированной ширины -->
                <div class="filter-search-group">
                    <div class="search-wrapper">
                        <input type="text" 
                               id="search" 
                               name="search" 
                               class="filter-search" 
                               placeholder="Поиск работ..."
                               value="<?php echo esc_attr($current_search); ?>">
                        <button type="button" class="search-clear" title="Очистить">×</button>
                    </div>
                </div>
                
                <!-- ОСНОВНЫЕ ФИЛЬТРЫ (всегда видны) - занимают оставшееся место -->
               <!-- ОСНОВНЫЕ ФИЛЬТРЫ (всегда видны) - занимают оставшееся место -->
<div class="main-filters">
    <!-- ФОРМА -->
    <div class="main-filter-group">
        <div class="main-filter-label">🎨 Форма</div>  <!-- ВОТ ЭТУ СТРОКУ ВЕРНУТЬ -->
        <div class="main-filter-buttons">
            <?php 
            $forms = get_terms(array(
                'taxonomy' => 'work_form', 
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            if ($forms && !is_wp_error($forms)) {
                foreach ($forms as $form) : 
                    $checked = in_array($form->slug, $current_forms) ? 'checked' : '';
                    $icon = deboart_get_form_icon($form->slug);
            ?>
                <label class="main-filter-button">
                    <input type="checkbox" 
                           name="form[]" 
                           value="<?php echo esc_attr($form->slug); ?>"
                           <?php echo $checked; ?>>
                    <span class="main-filter-icon"><?php echo $icon; ?></span>
                    <span class="main-filter-text"><?php echo esc_html($form->name); ?></span>
                </label>
            <?php 
                endforeach; 
            } else {
                echo '<p class="no-terms">Формы еще не созданы</p>';
            }
            ?>
        </div>
    </div>
    
    <!-- СОДЕРЖАНИЕ -->
    <div class="main-filter-group">
        <div class="main-filter-label">💭 Содержание</div>  <!-- И ЭТУ ТОЖЕ -->
        <div class="main-filter-buttons">
            <?php 
            $feelings = get_terms(array(
                'taxonomy' => 'work_feeling', 
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            if ($feelings && !is_wp_error($feelings)) {
                foreach ($feelings as $feeling) : 
                    $checked = in_array($feeling->slug, $current_feelings) ? 'checked' : '';
                    $icon = deboart_get_feeling_icon($feeling->slug);
            ?>
                <label class="main-filter-button">
                    <input type="checkbox" 
                           name="feeling[]" 
                           value="<?php echo esc_attr($feeling->slug); ?>"
                           <?php echo $checked; ?>>
                    <span class="main-filter-icon"><?php echo $icon; ?></span>
                    <span class="main-filter-text"><?php echo esc_html($feeling->name); ?></span>
                </label>
            <?php 
                endforeach; 
            } else {
                echo '<p class="no-terms">Содержание еще не создано</p>';
            }
            ?>
        </div>
    </div>
</div>
            </div>
            
           <!-- АККОРДЕОН: дополнительные фильтры -->
<div class="filters-accordion">
    <button type="button" class="accordion-toggle" id="accordion-toggle">
        <span class="toggle-icon">⚙️</span>
        <span class="toggle-text">Дополнительные фильтры</span>
        <span class="toggle-arrow">▼</span>
    </button>
    
    <div class="accordion-content" id="accordion-content">
        <div class="additional-filters-grid">
            <!-- Клиенты -->
            <div class="additional-filter">
                <label class="additional-filter-label">👥 Клиенты</label>
                <select name="client" class="filter-select">
                    <option value="">Все клиенты</option>
                    <?php 
                    $clients = get_terms(['taxonomy' => 'client', 'hide_empty' => true]);
                    if ($clients && !is_wp_error($clients)) {
                        foreach ($clients as $client) : 
                            $selected = isset($_GET['client']) && $_GET['client'] == $client->slug ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($client->slug); ?>" <?php echo $selected; ?>>
                            <?php echo esc_html($client->name); ?>
                        </option>
                    <?php endforeach; } ?>
                </select>
            </div>
            
            <!-- Коллаборации -->
            <div class="additional-filter">
                <label class="additional-filter-label">🤝 Коллаборации</label>
                <select name="collaboration" class="filter-select">
                    <option value="">Все коллаборации</option>
                    <?php 
                    $collaborations = get_terms(['taxonomy' => 'collaboration', 'hide_empty' => true]);
                    if ($collaborations && !is_wp_error($collaborations)) {
                        foreach ($collaborations as $collab) : 
                            $selected = isset($_GET['collaboration']) && $_GET['collaboration'] == $collab->slug ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($collab->slug); ?>" <?php echo $selected; ?>>
                            <?php echo esc_html($collab->name); ?>
                        </option>
                    <?php endforeach; } ?>
                </select>
            </div>
            
            <!-- Категории -->
            <div class="additional-filter">
                <label class="additional-filter-label">📂 Категории</label>
                <select name="category" class="filter-select">
                    <option value="">Все категории</option>
                    <?php 
                    $categories = get_terms(['taxonomy' => 'category', 'hide_empty' => true]);
                    if ($categories && !is_wp_error($categories)) {
                        foreach ($categories as $category) : 
                            $selected = isset($_GET['category']) && $_GET['category'] == $category->slug ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($category->slug); ?>" <?php echo $selected; ?>>
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; } ?>
                </select>
            </div>
        </div>
    </div>
</div>
            
            <!-- НИЖНЯЯ СТРОКА: статистика и кнопки -->
            <div class="filters-bottom-row">
                <div class="filter-stats">
                    <span class="stats-text">Найдено:</span>
                    <strong id="filtered-count"><?php echo $total_works; ?></strong>
                    <span class="stats-text">работ</span>
                </div>
                
                <div class="filter-actions">
                    <button type="button" class="filter-reset" id="reset-filters">
                        <span class="reset-icon">↺</span>
                        <span class="reset-text">Сбросить все</span>
                    </button>
                </div>
            </div>
            
            <!-- Скрытые поля для AJAX -->
            <input type="hidden" name="action" value="filter_works_simple">
            <input type="hidden" name="paged" value="1">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('filter_works_nonce'); ?>">
        </form>
    </div>

    <!-- Контейнер для результатов -->
    <div id="works-results-container" class="works-results-container">
        <?php 
        // Вместо оригинального кода с постами, используем шаблон works-grid.php
        // Определяем путь к шаблону
        $template_path = locate_template('template-parts/works-grid.php');
        
        if ($template_path) {
            include($template_path);
        } else {
            // Если шаблон не найден, выводим старый код для обратной совместимости
            if (have_posts()) : 
            ?>
			<!-- сетка работ, если шаблон не найден --> 
			<div class="works-grid-header">
                    <div class="works-stats">
                        <span class="stats-count">🕰️ Показано <?php echo $start; ?>—<?php echo $end; ?> из <?php echo $total_works; ?> работ</span>
                    </div>
                </div>
                
                <div class="works-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('parts/content', 'work-card'); ?>
                    <?php endwhile; ?>
                </div>
                
                <!-- Пагинация -->
                <div class="works-pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => __('← Предыдущая', 'tt4-deboart'),
                        'next_text' => __('Следующая →', 'tt4-deboart'),
                    ));
                    ?>
                </div>
            <?php else : ?>
                <div class="no-works-found">
                    <div class="no-results-icon">🔍</div>
                    <h3>Работы не найдены</h3>
                    <p>Попробуйте изменить параметры фильтрации</p>
                    <button type="button" class="no-results-reset" id="reset-from-empty">
                        Сбросить фильтры
                    </button>
                </div>
            <?php endif; 
        }
        ?>
    </div>
	</div>
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>