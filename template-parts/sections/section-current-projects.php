<?php
/**
 * Паттерн: Сейчас в работе
 * Выводит до 3 проектов со статусом 'in_progress'
 * Стилизован по аналогии с секцией "Исследования"
 */

// Получаем проекты в работе (до 3)
$current_projects = new WP_Query(array(
    'post_type'      => 'work',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => array(
        array(
            'key'   => 'work_status',
            'value' => 'in_progress',
        )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => true,
    'update_post_term_cache' => true
));

// Если нет проектов — ничего не выводим
if (!$current_projects->have_posts()) {
    return;
}
?>

<section class="front-section deboart-current-section">
    <div class="wp-block-group__inner-container">
        
        <h2 class="wp-block-heading has-text-align-center current-heading">СЕЙЧАС В РАБОТЕ</h2>
        
        <p class="has-text-align-center current-subtitle">
            Проекты, которые ещё не завершены, но уже существуют.<br>
            Следите за процессом — финал может быть ближе, чем кажется.
        </p>
        
        <div class="wp-block-group current-cards-container">
            
            <?php while ($current_projects->have_posts()) : $current_projects->the_post(); 
                $work_id = get_the_ID();
                
                // Получаем форму работы для иконки и названия
                $form_terms = get_the_terms($work_id, 'work_form');
                $form_icon = '🎨';
                $form_name = 'Работа';
                
                if ($form_terms && !is_wp_error($form_terms)) {
                    $first_term = reset($form_terms);
                    $form_icon = deboart_get_form_icon($first_term->slug);
                    $form_name = $first_term->name;
                }
                
                // Получаем описание (если есть, иначе берем отрывок)
                $description = get_post_meta($work_id, 'description', true);
                if (empty($description)) {
                    $description = get_the_excerpt();
                }
            ?>
            
            <div class="wp-block-group current-card">
                
                <div class="current-card-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('class' => 'current-card-img')); ?>
                    <?php else : ?>
                        <div class="current-card-placeholder">
                            <span class="current-card-placeholder-icon"><?php echo $form_icon; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="current-card-badge">
                        <span><?php echo $form_icon; ?></span>
                        <span><?php echo esc_html($form_name); ?></span>
                    </div>
                    
                    <!-- Добавляем бейдж "В работе" -->
                    <div class="current-card-status">
                        <span class="status-badge">В работе</span>
                    </div>
                </div>
                
                <div class="current-card-content">
                    <h3 class="current-card-title"><?php the_title(); ?></h3>
                    
                    <p class="current-card-description">
                        <?php echo esc_html(wp_trim_words($description, 20, '...')); ?>
                    </p>
                    
                    <div class="wp-block-button current-card-button">
                        <a href="<?php the_permalink(); ?>" class="wp-block-button__link">
                            Следить за процессом
                        </a>
                    </div>
                </div>
                
            </div>
            
            <?php endwhile; wp_reset_postdata(); ?>
            
        </div>
        
        <div class="wp-block-buttons">
            <div class="wp-block-button current-archive-button">
                <a href="<?php echo esc_url(home_url('/in-progress/')); ?>" class="wp-block-button__link">
                    Все проекты в работе →
                </a>
            </div>
        </div>
        
    </div>
</section>