<?php
/**
 * Шаблон карточки работы для архива
 * Версия: 3.0 (полная стилизация под theme.json)
 */

$post_id = get_the_ID();
//$year = get_post_meta($post_id, 'work_date', true);
$work_date = get_post_meta(get_the_ID(), 'work_date', true);
                if (!empty($work_date)) :
                    $year = $work_date;
                    if (strtotime($work_date)) $year = date('Y', strtotime($work_date));
                    elseif (preg_match('/\d{4}/', $work_date, $matches)) $year = $matches[0];
					endif;
$forms = get_the_terms($post_id, 'work_form');
$feelings = get_the_terms($post_id, 'work_feeling');
$excerpt = get_the_excerpt();
$thumbnail = get_the_post_thumbnail_url($post_id, 'medium_large');
$form_icons = array(
    'text'   => '📖',
    'image'  => '🖼️',
    'video'  => '🎬',
    'audio'  => '🎵',
    'web'    => '🌐',
    'object' => '✨'
);
$feeling_icons = array(
    'tishina' => '😌',
    'energy'  => '⚡',
    'thought' => '🤔',
    'drama'   => '🎭',
    'chaos'   => '🌀',
    'memory'  => '🕰️'
);
?>
<!-- Шаблон карточки работы для архива -->
<article class="work-card" data-id="<?php echo $post_id; ?>">
    <a href="<?php the_permalink(); ?>" class="work-card-link">
        
        <!-- ИЗОБРАЖЕНИЕ С ПЛЕЙСХОЛДЕРОМ -->
        <div class="work-card-image">
            <?php if ($thumbnail) : ?>
                <img src="<?php echo esc_url($thumbnail); ?>" 
                     alt="<?php the_title_attribute(); ?>"
                     loading="lazy">
            <?php else : ?>
                <div class="work-card-placeholder">
                    <div class="placeholder-content">
                        <span class="placeholder-icon">🎨</span>
                        <?php if (!empty($forms) && !is_wp_error($forms)) : 
                            $first_form = reset($forms);
                            $icon = isset($form_icons[$first_form->slug]) ? $form_icons[$first_form->slug] : '🎨';
                        ?>
                            <span class="placeholder-form"><?php echo $icon . ' ' . esc_html($first_form->name); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- КОНТЕНТ -->
        <div class="work-card-content">
            
            <!-- ЗАГОЛОВОК -->
            <h3 class="work-card-title">
                <?php the_title(); ?>
            </h3>
            
            <!-- МЕТА-ИНФОРМАЦИЯ В ОДНУ СТРОКУ -->
            <div class="work-card-meta">
                
                <!-- Год (всегда первым) -->
                <?php if ($year) : ?>
                    <span class="meta-item meta-year">
                        <span class="meta-icon">🕰️</span>
                        <span class="meta-text"><?php echo esc_html($year); ?></span>
                    </span>
                <?php endif; ?>
                
                <!-- Форма (иконка + название) -->
                <?php if ($forms && !is_wp_error($forms)) : ?>
                    <span class="meta-item meta-form">
                        <?php 
                        $first_form = reset($forms);
                        $icon = isset($form_icons[$first_form->slug]) ? $form_icons[$first_form->slug] : '🎨';
                        ?>
                        <span class="meta-icon"><?php echo $icon; ?></span>
                        <span class="meta-text"><?php echo esc_html($first_form->name); ?></span>
                        <?php if (count($forms) > 1) : ?>
                            <span class="meta-count">+<?php echo count($forms) - 1; ?></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
                
                <!-- Содержание (иконка + название) -->
                <?php if ($feelings && !is_wp_error($feelings)) : ?>
                    <span class="meta-item meta-feeling">
                        <?php 
                        $first_feeling = reset($feelings);
                        $icon = isset($feeling_icons[$first_feeling->slug]) ? $feeling_icons[$first_feeling->slug] : '💭';
                        ?>
                        <span class="meta-icon"><?php echo $icon; ?></span>
                        <span class="meta-text"><?php echo esc_html($first_feeling->name); ?></span>
                        <?php if (count($feelings) > 1) : ?>
                            <span class="meta-count">+<?php echo count($feelings) - 1; ?></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
                
            </div>
            
            <!-- ЭКСЦЕРПТ -->
            <?php if ($excerpt) : ?>
                <div class="work-card-excerpt">
                    <?php echo wp_trim_words($excerpt, 20, '...'); ?>
                </div>
            <?php endif; ?>
            
            <!-- КНОПКА -->
            <div class="work-card-actions">
                <span class="work-card-link-button">
                    <span class="link-text">Изучить протокол</span>
                    <span class="link-arrow">→</span>
                </span>
            </div>
            
        </div>
        
    </a>
</article>