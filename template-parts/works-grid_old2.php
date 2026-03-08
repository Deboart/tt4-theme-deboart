<?php
// template-parts/works-grid.php

global $wp_query;
$total_works = $wp_query->found_posts;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = get_option('posts_per_page');
$start = (($paged - 1) * $posts_per_page) + 1;
$end = min($paged * $posts_per_page, $total_works);
?>

<div class="works-grid-header">
    <div class="works-stats">
        <?php if ($total_works > 0) : ?>
            <span class="stats-count">🕰️ Показано <?php echo $start; ?>—<?php echo $end; ?> из <?php echo $total_works; ?> работ</span>
        <?php else : ?>
            <span class="stats-count">Работы не найдены</span>
        <?php endif; ?>
    </div>
</div>

<?php if (have_posts()) : ?>
    <div class="works-grid">
        <?php while (have_posts()) : the_post(); 
            // Получаем данные для карточки
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : '';
            $thumbnail_srcset = $thumbnail_id ? wp_get_attachment_image_srcset($thumbnail_id, 'medium') : '';
            $year = get_post_meta(get_the_ID(), 'work_year', true);
            
            // Получаем таксономии
            $forms = get_the_terms(get_the_ID(), 'work_form');
            $feelings = get_the_terms(get_the_ID(), 'work_feeling');
        ?>
            <article class="work-card" data-year="<?php echo esc_attr($year); ?>">
                <a href="<?php the_permalink(); ?>" class="work-card-link">
                    <?php if ($thumbnail_url) : ?>
                        <div class="work-card-image">
                            <img 
                                class="lazy" 
                                data-src="<?php echo esc_url($thumbnail_url); ?>"
                                data-srcset="<?php echo esc_attr($thumbnail_srcset); ?>"
                                alt="<?php the_title_attribute(); ?>"
                                width="400" 
                                height="300"
                            />
                            <noscript>
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>" />
                            </noscript>
                        </div>
                    <?php endif; ?>
                    
                    <div class="work-card-content">
                        <h3 class="work-card-title"><?php the_title(); ?></h3>
                        
                        <?php if ($year) : ?>
                            <div class="work-card-year"><?php echo esc_html($year); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($forms || $feelings) : ?>
                            <div class="work-card-taxonomies">
                                <?php if ($forms) : ?>
                                    <div class="work-card-forms">
                                        <?php foreach ($forms as $form) : ?>
                                            <span class="work-card-taxonomy work-form">
                                                <?php echo deboart_get_form_icon($form->slug); ?>
                                                <?php echo esc_html($form->name); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($feelings) : ?>
                                    <div class="work-card-feelings">
                                        <?php foreach ($feelings as $feeling) : ?>
                                            <span class="work-card-taxonomy work-feeling">
                                                <?php echo deboart_get_feeling_icon($feeling->slug); ?>
                                                <?php echo esc_html($feeling->name); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            </article>
        <?php endwhile; ?>
    </div>

    <!-- Пагинация -->
    <?php if ($total_works > $posts_per_page) : ?>
    <div class="works-pagination">
        <?php
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('← Предыдущая', 'tt4-deboart'),
            'next_text' => __('Следующая →', 'tt4-deboart'),
        ));
        ?>
    </div>
    <?php endif; ?>
<?php else : ?>
    <div class="no-works-found">
        <div class="no-results-icon">🔍</div>
        <h3>Работы не найдены</h3>
        <p>Попробуйте изменить параметры фильтрации</p>
        <button type="button" class="no-results-reset" id="reset-from-empty">
            Сбросить фильтры
        </button>
    </div>
<?php endif; ?>