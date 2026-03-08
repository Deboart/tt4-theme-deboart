<?php
/**
 * AJAX обработчик фильтрации работ
 */

function deboart_filter_works_simple() {
    // Проверка nonce
    if (!check_ajax_referer('filter_works_nonce', 'nonce', false)) {
        wp_die('Security check failed');
    }

    // Получаем параметры фильтрации
    $args = array(
        'post_type'      => 'work',
        'post_status'    => 'publish',
        'posts_per_page' => get_option('posts_per_page', 12),
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    // Пагинация
    if (isset($_POST['paged']) && intval($_POST['paged']) > 0) {
        $args['paged'] = intval($_POST['paged']);
    }

    // Поиск по названию и описанию
    if (!empty($_POST['search'])) {
        $args['s'] = sanitize_text_field($_POST['search']);
    }

    // Фильтрация по форме
    if (!empty($_POST['form'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'work_form',
            'field'    => 'slug',
            'terms'    => array_map('sanitize_title', (array)$_POST['form']),
            'operator' => 'IN'
        );
    }

    // Фильтрация по содержанию
    if (!empty($_POST['feeling'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'work_feeling',
            'field'    => 'slug',
            'terms'    => array_map('sanitize_title', (array)$_POST['feeling']),
            'operator' => 'IN'
        );
    }

    // Фильтрация по клиентам
    if (!empty($_POST['client'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'client',
            'field'    => 'slug',
            'terms'    => sanitize_title($_POST['client'])
        );
    }

    // Фильтрация по коллаборациям
    if (!empty($_POST['collaboration'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'collaboration',
            'field'    => 'slug',
            'terms'    => sanitize_title($_POST['collaboration'])
        );
    }

    // Фильтрация по категориям
    if (!empty($_POST['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => sanitize_title($_POST['category'])
        );
    }

    // Если есть несколько таксономий - настраиваем отношение
    if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    // Выполняем запрос
    $works_query = new WP_Query($args);
    
    // Генерируем HTML ответ
    ob_start();
    
    if ($works_query->have_posts()) {
        // Статистика
        $total_works = $works_query->found_posts;
        $paged = $args['paged'] ?? 1;
        $posts_per_page = $args['posts_per_page'];
        $start = (($paged - 1) * $posts_per_page) + 1;
        $end = min($paged * $posts_per_page, $total_works);
        ?>
        
        <div class="works-grid-header">
            <div class="works-stats">
                <span class="stats-count">🕰️ Показано <?php echo $start; ?>—<?php echo $end; ?> из <?php echo $total_works; ?> работ</span>
            </div>
        </div>
        
        <div class="works-grid">
            <?php while ($works_query->have_posts()) : $works_query->the_post(); ?>
                <?php get_template_part('parts/content', 'work-card'); ?>
            <?php endwhile; ?>
        </div>
        
        <!-- Пагинация -->
        <div class="works-pagination">
            <?php
            $big = 999999999;
            echo paginate_links(array(
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => max(1, $paged),
                'total'     => $works_query->max_num_pages,
                'prev_text' => __('← Предыдущая', 'tt4-deboart'),
                'next_text' => __('Следующая →', 'tt4-deboart'),
                'mid_size'  => 2
            ));
            ?>
        </div>
        
        <?php
        wp_reset_postdata();
    } else {
        // Нет результатов
        ?>
        <div class="no-works-found">
            <div class="no-results-icon">🔍</div>
            <h3>Работы не найдены</h3>
            <p>Попробуйте изменить параметры фильтрации</p>
            <button type="button" class="no-results-reset" id="reset-from-empty">
                Сбросить фильтры
            </button>
        </div>
        <?php
    }
    
    $html = ob_get_clean();
    
    // Возвращаем JSON ответ
    wp_send_json_success(array(
        'html' => $html,
        'count' => $works_query->found_posts,
        'max_pages' => $works_query->max_num_page,
		'paged' => $args['paged'] ?? 1
    ));
    
    wp_die();
}