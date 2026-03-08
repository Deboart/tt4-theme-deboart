<?php
/**
 * Секция "Метод" с миниатюрами работ
 */
$method_intro = get_theme_mod('method_intro', 'Я не мастер одной техники. У меня есть метод:');

// Получаем последние 9 работ для миниатюр
$works_query = new WP_Query(array(
    'post_type' => 'work',
    'posts_per_page' => 9,
    'post_status' => 'publish'
));
?>

<section class="front-section deboart-method-section" id="deboart-method-section">
<div class="grid-dots"></div>
    <div class="wp-block-group__inner-container">
        
        <h2 class="method-heading">МЕТОД</h2>
        
        <div class="wp-block-columns method-columns">
            
            <!-- Левая колонка с текстом -->
            <div class="wp-block-column method-text-column">
                <?php if ($method_intro) : ?>
                    <p class="method-intro"><?php echo esc_html($method_intro); ?></p>
                <?php endif; ?>
                
                <ul class="method-list">
                    <li class="method-list-item">Увидеть форму</li>
                    <li class="method-list-item">Спросить: «А что, если?»</li>
                    <li class="method-list-item">Погрузиться настолько, насколько хватает любопытства</li>
                    <li class="method-list-item">Зафиксировать результат как доказательство</li>
                </ul>
            </div>
            
            <!-- Правая колонка с галереей -->
            <div class="wp-block-column method-gallery-column">
                
                <?php if ($works_query->have_posts()) : ?>
                    <figure class="wp-block-gallery method-gallery">
                        
                        <?php 
                        while ($works_query->have_posts()) : $works_query->the_post(); 
                            $form_terms = get_the_terms(get_the_ID(), 'work_form');
                            $emoji = '';
                            if ($form_terms && !is_wp_error($form_terms)) {
                                $first_term = reset($form_terms);
                                $emoji = deboart_get_form_icon($first_term->slug);
                            }
                            
                            $year = get_post_meta(get_the_ID(), 'work_date', true);
                            $thumbnail = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                            $caption = $emoji . ' ' . get_the_title();
                            if ($year) {
                                $caption .= ' (' . $year . ')';
                            }
                        ?>
                        
                            <figure class="method-gallery-item">
                                <?php if ($thumbnail) : ?>
                                    <img src="<?php echo esc_url($thumbnail); ?>" 
                                         alt="<?php echo esc_attr(get_the_title()); ?>" 
                                         class="method-gallery-image"
                                         loading="lazy">
                                <?php else : ?>
                                    <div class="method-gallery-placeholder">
                                        <span class="method-gallery-emoji-large"><?php echo $emoji ?: '🎨'; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <figcaption class="method-caption">
                                    <?php echo $caption; ?>
                                </figcaption>
                                
                                <a href="<?php the_permalink(); ?>" class="method-gallery-link"></a>
                            </figure>
                            
                        <?php endwhile; wp_reset_postdata(); ?>
                        
                    </figure>
                    
                <?php else : ?>
                    <div class="method-gallery-placeholder-message">
                        <p>Отметьте работы как «Избранные» в админке, чтобы они появились здесь</p>
                    </div>
                <?php endif; ?>
                
            </div>
            
        </div>
        
    </div>
</section>