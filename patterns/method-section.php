<?php
/**
 * Title: Deboart Method Section
 * Slug: deboart/method-section
 * Categories: featured
 * Description: Секция "Метод" с избранными работами
 */

// Получаем избранные работы (до 9 штук)
$featured_works = new WP_Query(array(
    'post_type' => 'work',
    'posts_per_page' => 9,
    'meta_key' => 'featured',
    'meta_value' => 1,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
));
?>

<!-- wp:group {"align":"full","className":"deboart-method-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large"}}}} -->
<div class="wp-block-group alignfull deboart-method-section" style="padding-top:var(--wp--preset--spacing--x-large);padding-bottom:var(--wp--preset--spacing--x-large);">
    
    <!-- ВНУТРЕННИЙ КОНТЕЙНЕР С ОГРАНИЧЕНИЕМ ШИРИНЫ -->
    <div class="wp-block-group__inner-container" style="max-width:1400px; margin:0 auto; padding:0 20px;">
        
        <!-- Заголовок -->
        <!-- wp:heading {"level":2,"className":"method-heading"} -->
        <h2 class="wp-block-heading method-heading">МЕТОД</h2>
        <!-- /wp:heading -->
        
        <!-- КОЛОНКИ -->
        <!-- wp:columns {"className":"method-columns"} -->
        <div class="wp-block-columns method-columns">
            
            <!-- Левая колонка (текст) -->
            <!-- wp:column {"width":"35%","className":"method-text-column"} -->
            <div class="wp-block-column method-text-column" style="flex-basis:35%">
                
                <!-- wp:paragraph {"className":"method-intro"} -->
                <p class="method-intro">
                    Я не мастер одной техники.<br>
                    У меня есть метод:
                </p>
                <!-- /wp:paragraph -->
                
                <!-- wp:list {"className":"method-list"} -->
                <ul class="method-list">
                    <li class="method-list-item">Увидеть форму</li>
                    <li class="method-list-item">Спросить: «А что, если?»</li>
                    <li class="method-list-item">Погрузиться настолько, насколько хватает любопытства</li>
                    <li class="method-list-item">Зафиксировать результат как доказательство</li>
                </ul>
                <!-- /wp:list -->
                
            </div>
            <!-- /wp:column -->
            
            <!-- Правая колонка (галерея) -->
            <!-- wp:column {"width":"65%","className":"method-gallery-column"} -->
            <div class="wp-block-column method-gallery-column" style="flex-basis:65%">
                
                <!-- ГАЛЕРЕЯ -->
                <figure class="wp-block-gallery has-nested-images columns-3 method-gallery">
                    <?php if ($featured_works->have_posts()) : ?>
                        <?php while ($featured_works->have_posts()) : $featured_works->the_post(); 
                            $form_terms = get_the_terms(get_the_ID(), 'work_form');
                            $emoji = '';
                            if ($form_terms && !is_wp_error($form_terms)) {
                                $emoji = get_term_meta($form_terms[0]->term_id, 'emoji', true);
                            }
                            
                            $year = get_post_meta(get_the_ID(), 'year', true);
                            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            $caption = $emoji . ' ' . get_the_title();
                            if ($year) {
                                $caption .= ' (' . $year . ')';
                            }
                        ?>
                            <figure class="wp-block-image size-medium method-gallery-item">
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
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <div class="method-gallery-placeholder-message">
                            <p>Отметьте работы как «Избранные» в админке, чтобы они появились здесь</p>
                        </div>
                    <?php endif; ?>
                </figure>
                
            </div>
            <!-- /wp:column -->
            
        </div>
        <!-- /wp:columns -->
        
    </div>
    <!-- /wp:group__inner-container -->
    
</div>
<!-- /wp:group -->