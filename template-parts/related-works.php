<?php
/**
 * Template Part: Related Works
 * Description: Отображает связанные работы на основе общей парадигмы
 * 
 * @param int $current_work_id ID текущей работы
 */

// Получаем ID текущей работы из переданного параметра или глобально
$current_work_id = isset($current_work_id) ? $current_work_id : get_the_ID();

// Получаем таксономии текущей работы
$current_form_terms = get_the_terms($current_work_id, 'work_form');
$current_feeling_terms = get_the_terms($current_work_id, 'work_feeling');

$related_works = array();

// Если есть хотя бы одна таксономия
if (!empty($current_form_terms) || !empty($current_feeling_terms)) {
    
    $tax_query = array('relation' => 'OR');
    
    // Добавляем условия по формам
    if (!empty($current_form_terms) && !is_wp_error($current_form_terms)) {
        foreach ($current_form_terms as $term) {
            $tax_query[] = array(
                'taxonomy' => 'work_form',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            );
        }
    }
    
    // Добавляем условия по содержаниям
    if (!empty($current_feeling_terms) && !is_wp_error($current_feeling_terms)) {
        foreach ($current_feeling_terms as $term) {
            $tax_query[] = array(
                'taxonomy' => 'work_feeling',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            );
        }
    }
    
    // Запрос на связанные работы
    $related_query = new WP_Query(array(
        'post_type'      => 'work',
        'post_status'    => 'publish',
        'posts_per_page' => $related_count, //количество работ из шаблона работы
        'post__not_in'   => array($current_work_id),
        'tax_query'      => $tax_query,
        'orderby'        => 'rand',
        'no_found_rows'  => true,
    ));
    
    $related_works = $related_query->posts;
}

// Если нашли связанные работы
if (!empty($related_works)) : 
?>
<section class="work-section work-related">
    <div class="section-header">
        <h2 class="section-title">
            <span class="section-icon">🕸️</span>
            Связанные исследования
        </h2>
        <div class="section-subtitle">
            Другие работы с пересекающейся парадигмой
        </div>
    </div>
    
    <div class="section-content">
        <div class="related-grid">
            <?php foreach ($related_works as $related_work) : 
                setup_postdata($GLOBALS['post'] = $related_work);
                
                // Получаем иконки форм и содержаний
                $form_terms = get_the_terms($related_work->ID, 'work_form');
                $feeling_terms = get_the_terms($related_work->ID, 'work_feeling');
                
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
                
                $year = get_post_meta($related_work->ID, 'work_date', true);
            ?>
                <div class="related-card">
                    <a href="<?php echo get_permalink($related_work->ID); ?>" class="related-link">
                        <?php if (has_post_thumbnail($related_work->ID)) : ?>
                            <div class="related-image">
                                <?php echo get_the_post_thumbnail($related_work->ID, 'medium', array('class' => 'related-img')); ?>
                            </div>
                        <?php else : ?>
                            <div class="related-placeholder">
                                <span class="related-placeholder-icon">
                                    <?php echo !empty($icons) ? $icons[0] : '🎨'; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="related-content">
                            <div class="related-icons">
                                <?php foreach (array_slice($icons, 0, 3) as $icon) : ?>
                                    <span class="related-icon"><?php echo $icon; ?></span>
                                <?php endforeach; ?>
                                <?php if (count($icons) > 3) : ?>
                                    <span class="related-icon-more">+<?php echo count($icons) - 3; ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="related-title"><?php echo get_the_title($related_work->ID); ?></h3>
                            
                            <?php if ($year) : ?>
                                <span class="related-year"><?php echo esc_html($year); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; 
            wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>