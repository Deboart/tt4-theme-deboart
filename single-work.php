<?php
/**
 * Single Work Template for DEBOART
 * Template Post Type: work
 * Версия: 3.0 (модульная архитектура)
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';

// Вспомогательная функция для создания ссылки на архив с фильтром.
if (!function_exists('deboart_get_filtered_archive_url')) {
    function deboart_get_filtered_archive_url($taxonomy, $term_slug) {
        $base_url = get_post_type_archive_link('work');
        
        $args = array(
            'search' => '', // Пустой поиск, чтобы параметр был в URL
        );
        
        // Добавляем параметр в зависимости от таксономии
        if ($taxonomy === 'work_form') {
            $args['form'] = array($term_slug);
        } elseif ($taxonomy === 'work_feeling') {
            $args['feeling'] = array($term_slug);
        }
        
        return add_query_arg($args, $base_url);
    }
}

// Иконки форм (если не определены)
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
}

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// Определяем основную форму работы
$form_terms = get_the_terms($work_id, 'work_form');
$primary_form = 'text'; // fallback

if ($form_terms && !is_wp_error($form_terms)) {
    $forms = wp_list_pluck($form_terms, 'slug');
    $primary_form = $forms[0];
}
?>


<main class="deboart-work-main">
    


    <div class="deboart-work-container">

        <!-- Хлебные крошки -->
        <nav class="work-breadcrumbs">
            <a href="<?php echo home_url(); ?>">Главная</a>
            <span class="separator">/</span>
            <a href="<?php echo get_post_type_archive_link('work'); ?>">Исследования</a>
            <span class="separator">/</span>
            <span class="current"><?php the_title(); ?></span>
        </nav>

        <!-- Шапка работы (общая для всех форм) -->
        <header class="work-header">
    <h1 class="work-title"><?php the_title(); ?></h1>

    <!-- Единая мета-строка -->
    <div class="work-meta-row">
        <?php 
        $work_date = get_post_meta($work_id, 'work_date', true);
        if (!empty($work_date)) :
            $year = $work_date;
            if (strtotime($work_date)) $year = date('Y', strtotime($work_date));
            elseif (preg_match('/\d{4}/', $work_date, $matches)) $year = $matches[0];
        ?>
            <div class="work-year-badge">
                <span class="icon">⏳</span>
                <span class="text"><?php echo esc_html($year); ?></span>
            </div>
        <?php endif; ?>

        <?php
        $forms = get_the_terms($work_id, 'work_form');
        $feelings = get_the_terms($work_id, 'work_feeling');
        ?>

        <?php if ($forms && !is_wp_error($forms)) : ?>
            <div class="work-form-badge">
                <span class="label">Форма:</span>
                <div class="taxonomy-items">
                    <?php foreach ($forms as $term) : 
                        $icon = deboart_get_form_icon($term->slug);
                        $filter_url = deboart_get_filtered_archive_url('work_form', $term->slug);
                    ?>
                        <a href="<?php echo esc_url($filter_url); ?>" class="taxonomy-item">
                            <span class="taxonomy-icon"><?php echo $icon; ?></span>
                            <span class="taxonomy-name"><?php echo esc_html($term->name); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($feelings && !is_wp_error($feelings)) : ?>
            <div class="work-feeling-badge">
                <span class="label">Содержание:</span>
                <div class="taxonomy-items">
                    <?php foreach ($feelings as $term) : 
                        $icon = deboart_get_feeling_icon($term->slug);
                        $filter_url = deboart_get_filtered_archive_url('work_feeling', $term->slug);
                    ?>
                        <a href="<?php echo esc_url($filter_url); ?>" class="taxonomy-item">
                            <span class="taxonomy-icon"><?php echo $icon; ?></span>
                            <span class="taxonomy-name"><?php echo esc_html($term->name); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

        <!-- ============================================= -->
        <!-- 1. КОНТЕНТ В ЗАВИСИМОСТИ ОТ ФОРМЫ РАБОТЫ      -->
        <!-- ============================================= -->
        <?php
        $form_template = get_stylesheet_directory() . "/template-parts/work-content-{$primary_form}.php";
        if (file_exists($form_template)) {
            include $form_template;
        } else {
            include get_stylesheet_directory() . '/template-parts/work-content-text.php';
        }
        ?>

        <!-- ============================================= -->
        <!-- 2. ОБЩИЕ СЕКЦИИ (галерея, аудио, документы)   -->
        <!-- ============================================= -->
        <?php get_template_part('template-parts/work-common-sections'); ?>

        <!-- ============================================= -->
        <!-- 3. ТЕХНИЧЕСКИЕ ДЕТАЛИ, НАВИГАЦИЯ, КОММЕНТАРИИ -->
        <!-- ============================================= -->
        
        <!-- Технические детали -->
        <?php get_template_part('template-parts/work-technical-details'); ?>

        <!-- Связанные статьи из Лаборатории -->
        <?php
        $related_labs = get_post_meta($work_id, 'related_lab_entry', false);
        if (!empty($related_labs)) :
        ?>
            <div class="work-lab-links">
                <p class="work-lab-links__heading">→ Читать в Лаборатории:</p>
                <ul class="work-lab-links__list">
                    <?php foreach ($related_labs as $lab_id) : 
                        $lab_post = get_post($lab_id);
                        if ($lab_post) :
                    ?>
                        <li><a href="<?php echo get_permalink($lab_post); ?>">«<?php echo esc_html($lab_post->post_title); ?>»</a></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ul>
            </div>
        <?php endif; ?>


                <!-- ГРАФ СВЯЗЕЙ -->
        <?php get_template_part('template-parts/related-graph'); ?>

        <!-- Навигация между работами -->
        <nav class="work-navigation">
            <div class="nav-previous"><?php previous_post_link('%link', '← %title'); ?></div>
            <div class="nav-all"><a href="<?php echo get_post_type_archive_link('work'); ?>">📚 Все исследования</a></div>
            <div class="nav-next"><?php next_post_link('%link', '%title →'); ?></div>
        </nav>

        <!-- Комментарии -->
        <?php if (comments_open() || get_comments_number()) : ?>
            <section class="work-section work-comments">
                <div class="section-header">
                    <h2 class="section-title"><span class="section-icon">💬</span> Обсуждение</h2>
                    <div class="section-subtitle">Вопросы и рефлексия</div>
                </div>
                <div class="section-content"><?php comments_template(); ?></div>
            </section>
        <?php endif; ?>

    </div> <!-- .deboart-work-container -->
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>