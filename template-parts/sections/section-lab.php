<?php
/**
 * Секция "Лаборатория" - последние записи из дневника
 * 
 * Показывает 2 последние записи из категории "lab"
 */

// Получаем последние 2 записи лаборатории
// Вариант 1: Если используете категорию "lab" для обычных записей
$lab_query = new WP_Query(array(
    'post_type' => 'post',
    'category_name' => 'lab',
    'posts_per_page' => 2,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => true,
    'update_post_term_cache' => true
));

// Вариант 2: Если используете отдельный тип записи 'debo_lab_entry' (раскомментируйте если нужно)
// $lab_query = new WP_Query(array(
//     'post_type' => 'debo_lab_entry',
//     'posts_per_page' => 2,
//     'post_status' => 'publish',
//     'orderby' => 'date',
//     'order' => 'DESC',
//     'no_found_rows' => true
// ));
?>

<section class="front-section deboart-lab-section">
    <div class="wp-block-group__inner-container">
        
        <h2 class="wp-block-heading has-text-align-center lab-heading">ЛАБОРАТОРИЯ</h2>
        
        <p class="has-text-align-center lab-subtitle">
            Живой дневник процесса. Эскизы, заметки, неудачные эксперименты и неожиданные озарения.<br>
            Здесь рождаются исследования.
        </p>
        
        <?php if ($lab_query->have_posts()) : ?>
            <div class="wp-block-group lab-entries-container">
                
                <?php 
                $counter = 1;
                while ($lab_query->have_posts()) : $lab_query->the_post(); 
                    
                    // Получаем формы и содержания для иконок
                    $form_terms = get_the_terms(get_the_ID(), 'work_form');
                    $feeling_terms = get_the_terms(get_the_ID(), 'work_feeling');
                    $icons = array();
                    
                    if ($form_terms && !is_wp_error($form_terms)) {
                        foreach ($form_terms as $term) {
                            $icons[] = deboart_get_form_icon($term->slug);
                        }
                    }
                    
                    if ($feeling_terms && !is_wp_error($feeling_terms)) {
                        foreach ($feeling_terms as $term) {
                            $icons[] = deboart_get_feeling_icon($term->slug);
                        }
                    }
                    
                    // Если нет таксономий, используем стандартные иконки
                    if (empty($icons)) {
                        $icons = array('📝', '🔬');
                    }
                    
                    // Ограничиваем до 2 иконок
                    $icons = array_slice($icons, 0, 2);
                ?>
                
                <div class="wp-block-group lab-entry-card">
                    
                    <h3 class="lab-entry-title">
                        <span class="lab-entry-icons">
                            <?php foreach ($icons as $icon) : ?>
                                <span><?php echo $icon; ?></span>
                            <?php endforeach; ?>
                        </span>
                        <span><?php the_title(); ?></span>
                    </h3>
                    
                    <p class="lab-entry-description">
                        <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                    </p>
                    
                    <div class="lab-entry-meta">
                        <p class="lab-entry-date"><?php echo get_the_date('Y-m-d'); ?></p>
                        
                        <div class="wp-block-button lab-entry-button">
                            <a href="<?php the_permalink(); ?>" class="wp-block-button__link">
                                Читать
                            </a>
                        </div>
                    </div>
                    
                </div>
                
                <?php 
                $counter++;
                endwhile; 
                wp_reset_postdata(); 
                ?>
                
            </div>
            
            <p class="has-text-align-center lab-all-entries">
                <div class="wp-block-button lab-all-entries-link">
                    <a href="<?php echo get_category_link(get_category_by_slug('lab')); ?>" class="wp-block-button__link">
                        Все записи лаборатории
                    </a>
                </div>
            </p>
            
        <?php else : ?>
            <p class="has-text-align-center lab-empty">Записи лаборатории скоро появятся</p>
        <?php endif; ?>
        
    </div>
</section>