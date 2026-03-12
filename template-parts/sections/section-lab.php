<?php
/**
 * Секция "Лаборатория" - последние записи из дневника
 * 
 * Показывает 2 последние записи из типа записи "lab"
 */

// Получаем последние 2 записи лаборатории из custom post type 'lab'
$lab_query = new WP_Query(array(
    'post_type' => 'lab',              // Тип записи lab
    'posts_per_page' => 2,              // 2 записи
    'post_status' => 'publish',         // Только опубликованные
    'orderby' => 'date',                 // Сортировка по дате
    'order' => 'DESC',                   // От новых к старым
    'no_found_rows' => true,              // Оптимизация (не нужна пагинация)
    'update_post_meta_cache' => true,     // Кэшируем мета-поля
    'update_post_term_cache' => true      // Кэшируем таксономии
));

// Функция для иконок, если не определена
if (!function_exists('deboart_get_form_icon')) {
    function deboart_get_form_icon($slug) {
        $icons = array(
            'text'   => '📖',
            'image'  => '🖼️',
            'video'  => '🎬',
            'audio'  => '🎵',
            'web'    => '🌐',
            'object' => '✨'
        );
        return isset($icons[$slug]) ? $icons[$slug] : '🎨';
    }
}

if (!function_exists('deboart_get_feeling_icon')) {
    function deboart_get_feeling_icon($slug) {
        $icons = array(
            'tishina' => '😌',
            'energy'  => '⚡',
            'thought' => '🤔',
            'drama'   => '🎭',
            'chaos'   => '🌀',
            'memory'  => '🕰️'
        );
        return isset($icons[$slug]) ? $icons[$slug] : '💭';
    }
}
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
                    
                    // Получаем таксономии лаборатории (не работ)
                    $category_terms = get_the_terms(get_the_ID(), 'lab_category');
                    $tag_terms = get_the_terms(get_the_ID(), 'lab_tag');
                    
                    $icons = array();
                    
                    // Используем первую букву категории как иконку, если нет специальных
                    if ($category_terms && !is_wp_error($category_terms) && !empty($category_terms)) {
                        $icons[] = '📂'; // Папка для категории
                    }
                    
                    if ($tag_terms && !is_wp_error($tag_terms) && !empty($tag_terms)) {
                        $icons[] = '🏷️'; // Тег для метки
                    }
                    
                    // Если нет таксономий, используем стандартные иконки лаборатории
                    if (empty($icons)) {
                        $icons = array('🧪', '📝');
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
                        <?php 
                        if (has_excerpt()) {
                            echo wp_trim_words(get_the_excerpt(), 30, '...');
                        } else {
                            echo wp_trim_words(strip_tags(get_the_content()), 25, '...');
                        }
                        ?>
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
                    <a href="<?php echo get_post_type_archive_link('lab'); ?>" class="wp-block-button__link">
                        Все записи лаборатории
                    </a>
                </div>
            </p>
            
        <?php else : ?>
            <p class="has-text-align-center lab-empty">Записи лаборатории скоро появятся</p>
        <?php endif; ?>
        
    </div>
</section>