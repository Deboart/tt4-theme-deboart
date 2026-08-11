<?php
/**
 * Template Name: Стихи
 * Страница со списком стихотворений
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';

 ?>

<main id="primary" class="site-main deboart-page deboart-stihi-page">
    <div class="deboart-page-container">
        
        <!-- Шапка страницы -->
        <header class="page-header">
            <div class="page-header-row">
                <h1 class="page-title has-hero-font-size">🧪 Стихи</h1>
            </div>
            <div class="page-intro">
                <p class="page-intro-text">
                    Стихотворения — особая форма исследований языка и чувства.<br>
                    Здесь они собраны как единое поле для чтения.
                </p>
            </div>
        </header>

        <!-- Лента стихов -->
        <div class="stihi-feed">
            <?php
            $stihi_query = new WP_Query(array(
            'post_type' => 'work',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => 'pojezija'
                )
            ),
            'meta_key' => 'work_date',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ));

            if ($stihi_query->have_posts()) :
                while ($stihi_query->have_posts()) : $stihi_query->the_post();
                            // ПОЛУЧАЕМ ОБЛОЖКУ (без Pods)
        $cover_id = get_post_meta(get_the_ID(), 'main_preview', true);
        if (empty($cover_id)) {
            $cover_id = get_post_thumbnail_id();
        }
        
        
        
        $cover_url = wp_get_attachment_image_url($cover_id, 'medium');
                    
                    $work_date = get_post_meta(get_the_ID(), 'work_date', true);
                    $year = '';
                    if (!empty($work_date)) {
                        if (strtotime($work_date)) $year = date('Y', strtotime($work_date));
                        elseif (preg_match('/\d{4}/', $work_date, $matches)) $year = $matches[0];
                    }
            ?>
                <article class="stihi-item">
                    <header class="stihi-item-header">
                        <h2 class="stihi-item-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                    </header>

                    <?php if ($cover_url) : ?>
                        <div class="stihi-item-cover">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url($cover_url); ?>" 
                                     alt="<?php echo esc_attr(get_the_title()); ?>"
                                     loading="lazy">
                            </a>
                        </div>
                    <?php endif; ?>

<div class="stihi-item-excerpt">
    <?php
    // Получаем полный контент с сохранением форматирования
    $full_content = get_the_content();
    
    // Удаляем шорткоды
    $full_content = preg_replace('/\[.*\]/', '', $full_content);
    
    // Разбиваем на строки по <br> или \n
    $lines = preg_split('/<br\s*\/?>/i', $full_content);
    if (count($lines) === 1) {
        $lines = explode("\n", strip_tags($full_content));
    }
    
    // Берём первые 15 строк (или сколько нужно)
    $max_lines = 100;
    $excerpt_lines = array_slice($lines, 0, $max_lines);
    $excerpt = implode('<br>', $excerpt_lines);
    
    // Если строк больше, чем взяли — добавляем ссылку «читать полностью»
    $has_more = count($lines) > $max_lines;
    
    echo wp_kses_post($excerpt);
    ?>
    
    <?php if ($has_more) : ?>
        <br><a href="<?php the_permalink(); ?>" class="stihi-read-more">читать полностью →</a>
    <?php endif; ?>
</div>

                    <?php if ($year) : ?>
                        <div class="stihi-item-year">
                            <span class="stihi-year-icon">⏳</span>
                            <span class="stihi-year-text"><?php echo esc_html($year); ?></span>
                        </div>
                    <?php endif; ?>
                </article>
            <?php 
                endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="stihi-empty">
                    <p>Стихотворения пока не добавлены.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>