<?php
/**
 * Single Lab Template for DEBOART
 * Template Post Type: lab
 * Версия: 1.0 (с обложкой)
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';

// Получаем ID записи и объект Pods для всех секций
$lab_id = get_the_ID();
$pods = pods('lab', $lab_id);
?>

<!-- Основной контейнер -->
<main class="deboart-lab-main">

    <!-- Минимальная верхняя навигация -->
    <nav class="lab-top-nav">
        <a href="<?php echo home_url('/'); ?>" class="nav-home">
            <span class="nav-icon">←</span>
            <span>DEBOART</span>
        </a>
        
        <div class="nav-actions">
            <a href="<?php echo get_post_type_archive_link('lab'); ?>" class="nav-archive">
                <span>🧪</span>
                <span>Все записи</span>
            </a>
        </div>
    </nav>

    <!-- Обложка с заголовком -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="lab-cover">
            <?php the_post_thumbnail('full', array('class' => 'lab-cover__image')); ?>
            <div class="lab-cover__overlay">
                <div class="container">
                    <div class="lab-cover__content">
                        <div class="lab-cover__meta">
                            <span class="lab-cover__date"><?php echo get_the_date('d.m.Y'); ?></span>
                            <?php 
                            $reading_time = get_post_meta($lab_id, 'reading_time', true);
                            if ($reading_time) : ?>
                                <span class="lab-cover__reading-time"><?php echo $reading_time; ?> мин чтения</span>
                            <?php endif; ?>
                        </div>
                        <h1 class="lab-cover__title"><?php the_title(); ?></h1>
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="lab-cover__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="deboart-lab-container">
        
        <!-- Хлебные крошки (если нет обложки) -->
        <?php if (!has_post_thumbnail()) : ?>
        <nav class="lab-breadcrumbs">
            <a href="<?php echo home_url(); ?>">Главная</a>
            <span class="separator">/</span>
            <a href="<?php echo get_post_type_archive_link('lab'); ?>">Лаборатория</a>
            <span class="separator">/</span>
            <span class="current"><?php the_title(); ?></span>
        </nav>
        <?php endif; ?>

        <!-- Шапка работы (если нет обложки) -->
        <?php if (!has_post_thumbnail()) : ?>
        <header class="lab-header">
            <div class="lab-meta-top">
                <div class="lab-date">
                    <span class="icon">📅</span>
                    <span class="text"><?php echo get_the_date('d.m.Y'); ?></span>
                </div>
                
                <?php 
                $reading_time = get_post_meta($lab_id, 'reading_time', true);
                if ($reading_time) : ?>
                <div class="lab-reading-time">
                    <span class="icon">⏱️</span>
                    <span class="text"><?php echo $reading_time; ?> мин</span>
                </div>
                <?php endif; ?>
            </div>

            <h1 class="lab-title"><?php the_title(); ?></h1>

            <?php if (has_excerpt()) : ?>
                <div class="lab-excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </header>
        <?php endif; ?>

        <!-- Основной контент -->
        <article class="lab-content">
            <?php the_content(); ?>
        </article>

        <!-- Таксономии (категория и теги) -->
        <div class="lab-taxonomies">
            <?php 
            // Категория лаборатории
            $categories = wp_get_post_terms($lab_id, 'lab_category');
            if ($categories && !is_wp_error($categories)) : 
                $category = $categories[0];
            ?>
                <div class="taxonomy-group">
                    <span class="taxonomy-label">Рубрика:</span>
                    <div class="taxonomy-items">
                        <a href="<?php echo get_term_link($category); ?>" class="taxonomy-item">
                            <span class="taxonomy-icon">📂</span>
                            <span class="taxonomy-name"><?php echo esc_html($category->name); ?></span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php 
            // Теги лаборатории
            $tags = wp_get_post_terms($lab_id, 'lab_tag');
            if ($tags && !is_wp_error($tags)) : 
            ?>
                <div class="taxonomy-group">
                    <span class="taxonomy-label">Теги:</span>
                    <div class="taxonomy-items">
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo get_term_link($tag); ?>" class="taxonomy-item">
                                <span class="taxonomy-icon">#</span>
                                <span class="taxonomy-name"><?php echo esc_html($tag->name); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Связанные работы -->
        <?php
        $related_data = $pods->field('related_works');
        
        $work_ids = array();
        
        if (!empty($related_data) && is_array($related_data)) {
            foreach ($related_data as $item) {
                if (is_array($item) && isset($item['ID'])) {
                    $work_ids[] = $item['ID'];
                } elseif (is_object($item) && isset($item->ID)) {
                    $work_ids[] = $item->ID;
                } elseif (is_numeric($item)) {
                    $work_ids[] = $item;
                }
            }
        }
        
        $work_ids = array_unique($work_ids);
        ?>

        <?php if (!empty($work_ids)) : ?>
            <section class="lab-section lab-related-works">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">🧪</span>
                        Связанные исследования
                    </h2>
                    <div class="section-subtitle">Работы, упомянутые в этой записи</div>
                </div>
                
                <div class="section-content">
                    <div class="related-works-grid">
                        <?php foreach ($work_ids as $work_id) : 
                            $work_title = get_the_title($work_id);
                            $work_link = get_permalink($work_id);
                            $work_thumbnail = get_the_post_thumbnail($work_id, 'thumbnail');
                            
                            if (empty($work_title)) continue;
                        ?>
                            <div class="related-work-card">
                                <a href="<?php echo esc_url($work_link); ?>">
                                    <?php if ($work_thumbnail) : ?>
                                        <div class="related-work-image">
                                            <?php echo $work_thumbnail; ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="related-work-title"><?php echo esc_html($work_title); ?></h3>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Единая навигация между записями -->
        <nav class="lab-navigation">
            <div class="nav-previous">
                <?php previous_post_link('%link', '← %title'); ?>
            </div>
            <div class="nav-all">
                <a href="<?php echo get_post_type_archive_link('lab'); ?>">
                    🧪 Все записи лаборатории
                </a>
            </div>
            <div class="nav-next">
                <?php next_post_link('%link', '%title →'); ?>
            </div>
        </nav>

        <!-- Комментарии -->
        <?php if (comments_open() || get_comments_number()) : ?>
        <section class="lab-section lab-comments">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">💬</span>
                    Обсуждение
                </h2>
                <div class="section-subtitle">Вопросы и рефлексия</div>
            </div>
            <div class="section-content">
                <?php comments_template(); ?>
            </div>
        </section>
        <?php endif; ?>

    </div> <!-- .deboart-lab-container -->
    
</main> <!-- .deboart-lab-main -->

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>