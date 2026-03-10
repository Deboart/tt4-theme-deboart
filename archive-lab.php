<?php
/**
 * Template Name: Archive Lab
 * Description: Архив записей лаборатории
 */

// Подключаем header (если используешь такую же структуру как в archive-work)
require get_stylesheet_directory() . '/template-parts/site-header.php';
?>

<main id="primary" class="site-main lab-archive">
    <div class="container">
        
        <div class="archive-header">
            <h1 class="archive-title">🧪 Лаборатория</h1>
            
            <!-- Статистика записей -->
            <?php 
            global $wp_query;
            $total_posts = $wp_query->found_posts;
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $posts_per_page = get_option('posts_per_page');
            $start = (($paged - 1) * $posts_per_page) + 1;
            $end = min($paged * $posts_per_page, $total_posts);
            ?>
            
            <div class="archive-stats">
                <p class="archive-description">
                    <span class="stats-count">📄 Показано <?php echo $start; ?>—<?php echo $end; ?> из <?php echo $total_posts; ?> записей</span>
                    <span class="stats-sort">Сортировка: по дате (новые → старые)</span>
                </p>
            </div>
        </div>

        <!-- Контейнер для результатов -->
        <div id="lab-results-container" class="lab-results-container">
            <?php if (have_posts()) : ?>
                
                <div class="lab-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        
<article id="post-<?php the_ID(); ?>" <?php post_class('lab-card'); ?> onclick="window.location='<?php the_permalink(); ?>';" style="cursor: pointer;">
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="lab-card__image">
            <a href="<?php the_permalink(); ?>" tabindex="-1">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="lab-card__content">
        <h2 class="lab-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        
        <div class="lab-card__meta">
            <span class="lab-card__date"><?php echo get_the_date('d.m.Y'); ?></span>
            
            <?php 
            $reading_time = get_post_meta(get_the_ID(), 'reading_time', true);
            if ($reading_time) : ?>
                <span class="lab-card__reading-time"><?php echo $reading_time; ?> мин</span>
            <?php endif; ?>
        </div>
        
        <div class="lab-card__excerpt">
            <?php the_excerpt(); ?>
        </div>
        
        <!-- Категория лаборатории (вместо тегов) -->
        <?php 
        $categories = wp_get_post_terms(get_the_ID(), 'lab_category');
        if ($categories && !is_wp_error($categories)) : 
            $category = $categories[0]; // Берем первую категорию
        ?>
            <div class="lab-card__category">
                <a href="<?php echo get_term_link($category); ?>" class="lab-category">
                    <span class="category-icon">📂</span>
                    <?php echo $category->name; ?>
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Кнопка как в карточке работы -->
        <div class="lab-card__footer">
            <a href="<?php the_permalink(); ?>" class="lab-card__button">
                <span class="button-text">Читать</span>
                <span class="button-arrow">→</span>
            </a>
        </div>
    </div>
</article>
                        
                    <?php endwhile; ?>
                </div>
                
                <!-- Пагинация -->
                <div class="lab-pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => __('← Предыдущая', 'tt4-deboart'),
                        'next_text' => __('Следующая →', 'tt4-deboart'),
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                <div class="no-posts-found">
                    <div class="no-results-icon">🧪</div>
                    <h3>Записей пока нет</h3>
                    <p>Скоро здесь появятся исследования и мысли</p>
                </div>
            <?php endif; ?>
        </div>
        
    </div>   
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>