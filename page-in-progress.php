<?php
/**
 * Template Name: Проекты в работе
 * 
 * Упрощённая страница для отображения проектов со статусом 'in_progress'
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';
?>

<main id="primary" class="site-main archive-work">
       <div class="work-archive__content">
        <div class="in-progress-header">
            <h1 class="in-progress-title">В РАБОТЕ</h1>
            <div class="in-progress-description">
                <p>Проекты, которые ещё не завершены, но уже существуют.<br>Следите за процессом — финал может быть ближе, чем кажется.</p>
            </div>
        </div>

        <?php
        // Создаём кастомный запрос для проектов в работе
        $in_progress_query = new WP_Query(array(
            'post_type'      => 'work',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'   => 'work_status',
                    'value' => 'in_progress',
                )
            )
        ));

        if ($in_progress_query->have_posts()) :
            // Сохраняем оригинальный глобальный запрос
            global $wp_query;
            $original_query = $wp_query;
            
            // Подменяем глобальный запрос на наш
            $wp_query = $in_progress_query;
            
            // Подключаем шаблон сетки (он будет использовать наш подменённый запрос)
            get_template_part('template-parts/works-grid');
            
            // Восстанавливаем оригинальный запрос
            $wp_query = $original_query;
            wp_reset_postdata();
        else :
            ?>
            <div class="in-progress-empty">
                <p>Пока нет активных проектов. Загляните позже — мастерская не простаивает.</p>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php require get_stylesheet_directory() . '/template-parts/site-footer.php'; ?>