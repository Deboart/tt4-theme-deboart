<?php
/**
 * Шаблон карточки работы для архива
 * Версия: 4.3 (год внизу с иконкой)
 */

$post_id = get_the_ID();
$work_date = get_post_meta(get_the_ID(), 'work_date', true);
$year = '';
if (!empty($work_date)) :
    $year = $work_date;
    if (strtotime($work_date)) $year = date('Y', strtotime($work_date));
    elseif (preg_match('/\d{4}/', $work_date, $matches)) $year = $matches[0];
endif;

$forms = get_the_terms($post_id, 'work_form');
$feelings = get_the_terms($post_id, 'work_feeling');
$excerpt = get_the_excerpt();
$thumbnail = get_the_post_thumbnail_url($post_id, 'medium_large');
$client = get_the_terms($post_id, 'client');

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

<article class="work-card" data-id="<?php echo $post_id; ?>">
    <a href="<?php the_permalink(); ?>" class="work-card-link">
        
        <!-- ИЗОБРАЖЕНИЕ -->
        <div class="work-card-image">
            <?php if ($thumbnail) : ?>
                <img src="<?php echo esc_url($thumbnail); ?>" 
                     alt="<?php the_title_attribute(); ?>"
                     loading="lazy">
            <?php else : ?>
                <div class="work-card-placeholder">
                    <div class="placeholder-grid">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                    <span class="placeholder-label">[NO IMAGE]</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- КОНТЕНТ -->
        <div class="work-card-content">
            
            <!-- ЗАГОЛОВОК -->
            <h3 class="work-card-title">
                <?php the_title(); ?>
            </h3>
            
<!-- БЕЙДЖИ -->
<div class="work-card-badges">
    
    <!-- Форма (бейдж) -->
    <?php if ($forms && !is_wp_error($forms)) : 
        $first_form = reset($forms);
        $icon = isset($form_icons[$first_form->slug]) ? $form_icons[$first_form->slug] : '○';
    ?>
        <span class="badge badge-form" title="<?php echo esc_attr($first_form->name); ?>">
            <?php echo $icon; ?> <!-- Эмодзи прямо тут, без доп. span -->
            <span class="badge-text"><?php echo esc_html($first_form->name); ?></span>
            <?php if (count($forms) > 1) : ?>
                <span class="badge-count">+<?php echo count($forms) - 1; ?></span>
            <?php endif; ?>
        </span>
    <?php endif; ?>
    
    <!-- Содержание  (бейдж) -->
    <?php if ($feelings && !is_wp_error($feelings)) : 
        $first_feeling = reset($feelings);
        $icon = isset($feeling_icons[$first_feeling->slug]) ? $feeling_icons[$first_feeling->slug] : '□';
    ?>
        <span class="badge badge-feeling" title="<?php echo esc_attr($first_feeling->name); ?>">
            <?php echo $icon; ?> <!-- Эмодзи прямо тут -->
            <span class="badge-text"><?php echo esc_html($first_feeling->name); ?></span>
            <?php if (count($feelings) > 1) : ?>
                <span class="badge-count">+<?php echo count($feelings) - 1; ?></span>
            <?php endif; ?>
        </span>
    <?php endif; ?>
                
                <!-- Клиент (бейдж, если есть) -->
                <?php if ($client && !is_wp_error($client)) : 
                    $first_client = reset($client);
                ?>
                    <span class="badge badge-client" title="<?php echo esc_attr($first_client->name); ?>">
                        <span class="badge-icon">👥</span>
                        <span class="badge-text"><?php echo esc_html($first_client->name); ?></span>
                        <?php if (count($client) > 1) : ?>
                            <span class="badge-count">+<?php echo count($client) - 1; ?></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
                
            </div>
            
            <!-- ЭКСЦЕРПТ -->
            <?php if ($excerpt) : ?>
                <div class="work-card-excerpt">
                    <?php echo wp_trim_words($excerpt, 15, '...'); ?>
                </div>
            <?php endif; ?>
            
<!-- НИЖНЯЯ СТРОКА: кнопка + год -->
<div class="work-card-footer">
    
    <!-- КНОПКА (теперь ярче) -->
    <div class="work-card-actions">
        <span class="work-card-link-button">
            <span class="link-text">Изучить</span>
            <span class="link-arrow">→</span>
        </span>
    </div>
    
    <!-- ГОД (теперь приглушённый) -->
    <?php if ($year) : ?>
        <div class="work-card-year">
            <span class="year-icon">🕰️</span>
            <span class="year-value"><?php echo esc_html($year); ?></span>
        </div>
    <?php endif; ?>
    
</div>
            
        </div>
        
    </a>
</article>