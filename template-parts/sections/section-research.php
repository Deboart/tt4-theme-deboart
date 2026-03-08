<?php
/**
 * Секция "Исследования" - три последние работы
 */

// Получаем 3 последние работы
$research_query = new WP_Query(array(
    'post_type' => 'work',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => true,
    'update_post_term_cache' => true
));
?>

<section class="front-section deboart-research-section">
    <div class="wp-block-group__inner-container">
        
        <h2 class="wp-block-heading has-text-align-center research-heading">ИССЛЕДОВАНИЯ</h2>
        
        <p class="has-text-align-center research-subtitle">
            Каждая работа — это законченное исследование конкретного вопроса.<br>
            Ниже — три последних эксперимента, раскрывающих метод.
        </p>
        
        <?php if ($research_query->have_posts()) : ?>
            <div class="wp-block-group research-cards-container">
                
                <?php while ($research_query->have_posts()) : $research_query->the_post(); 
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
                    
                    // Получаем год (может пригодиться)
                    $year = get_post_meta($work_id, 'work_date', true);
                ?>
                
                <div class="wp-block-group research-card">
                    
                    <div class="research-card-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array('class' => 'research-card-img')); ?>
                        <?php else : ?>
                            <div class="research-card-placeholder">
                                <span class="research-card-placeholder-icon"><?php echo $form_icon; ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="research-card-badge">
                            <span><?php echo $form_icon; ?></span>
                            <span><?php echo esc_html($form_name); ?></span>
                        </div>
                    </div>
                    
                    <div class="research-card-content">
                        <h3 class="research-card-title"><?php the_title(); ?></h3>
                        
                        <p class="research-card-description">
                            <?php echo esc_html(wp_trim_words($description, 20, '...')); ?>
                        </p>
                        
                        <div class="wp-block-button research-card-button">
                            <a href="<?php the_permalink(); ?>" class="wp-block-button__link">
                                Изучить протокол
                            </a>
                        </div>
                    </div>
                    
                </div>
                
                <?php endwhile; wp_reset_postdata(); ?>
                
            </div>
            
            <div class="wp-block-buttons">
                <div class="wp-block-button research-archive-button">
                    <a href="<?php echo get_post_type_archive_link('work'); ?>" class="wp-block-button__link">
                        Все исследования →
                    </a>
                </div>
            </div>
            
        <?php else : ?>
            <p class="research-empty">Работы еще не добавлены</p>
        <?php endif; ?>
        
    </div>
</section>