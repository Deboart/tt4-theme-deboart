<?php
/**
 * Контент для работы с формой "Веб"
 * Акцент — на iframe, скриншотах или ссылке на проект
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// Поле для URL проекта (нужно добавить в Pods, если ещё нет)
$project_url = get_post_meta($work_id, 'project_url', true);

// Скриншоты из галереи
$gallery_data = $pods->field('image_gallery');
$screenshot_ids = [];

if (!empty($gallery_data) && is_array($gallery_data)) {
    foreach ($gallery_data as $item) {
        if (is_array($item) && isset($item['ID'])) $screenshot_ids[] = $item['ID'];
        elseif (is_object($item) && isset($item->ID)) $screenshot_ids[] = $item->ID;
        elseif (is_numeric($item)) $screenshot_ids[] = $item;
    }
}
$screenshot_ids = array_unique($screenshot_ids);
?>

<!-- iframe ИЛИ ССЫЛКА (основной акцент) -->
<?php if (!empty($project_url)) : ?>
    <section class="work-section work-web-primary">
        <div class="web-primary-container">
            <?php 
            // Проверяем, можно ли встроить iframe (не запрещено X-Frame-Options)
            $parsed_url = parse_url($project_url);
            $allowed_hosts = ['youtube.com', 'youtu.be', 'vimeo.com', 'soundcloud.com', 'figma.com'];
            $is_embedable = false;
            
            foreach ($allowed_hosts as $host) {
                if (strpos($project_url, $host) !== false) {
                    $is_embedable = true;
                    break;
                }
            }
            
            if ($is_embedable) : ?>
                <div class="web-iframe-wrapper">
                    <iframe src="<?php echo esc_url($project_url); ?>" 
                            title="<?php echo esc_attr(get_the_title()); ?>"
                            frameborder="0"
                            allowfullscreen
                            loading="lazy">
                    </iframe>
                </div>
            <?php else : ?>
                <div class="web-link-wrapper">
                    <a href="<?php echo esc_url($project_url); ?>" class="web-project-link" target="_blank" rel="noopener noreferrer">
                        <span class="web-link-icon">🔗</span>
                        <span class="web-link-text">Открыть проект →</span>
                    </a>
                    <p class="web-link-note">Проект доступен по ссылке (откроется в новом окне)</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<!-- ГАЛЕРЕЯ СКРИНШОТОВ (если есть) -->
<?php if (!empty($screenshot_ids)) : ?>
    <section class="work-section work-web-screenshots">
        <div class="section-header">
            <h2 class="section-title"><span class="section-icon">🖼️</span> Скриншоты</h2>
            <div class="section-subtitle">Визуальное воплощение</div>
        </div>
        <div class="section-content">
            <div class="screenshots-grid">
                <?php foreach ($screenshot_ids as $image_id) : 
                    $image_url = wp_get_attachment_image_url($image_id, 'large');
                    if ($image_url) : 
                        $full_url = wp_get_attachment_image_url($image_id, 'full');
                        $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                ?>
                    <div class="screenshot-item">
                        <a href="<?php echo esc_url($full_url); ?>" data-lightbox="web-gallery" data-title="<?php echo esc_attr($alt); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy">
                        </a>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- КРАТКОЕ ОПИСАНИЕ / ТЕГЛАЙН -->
<?php
$short_description = get_post_meta($work_id, 'short_description', true);
if (!empty($short_description)) : ?>
    <section class="work-section work-teaser">
        <div class="section-content">
            <p class="teaser-text"><?php echo esc_html($short_description); ?></p>
        </div>
    </section>
<?php endif; ?>

<!-- ОСНОВНОЙ КОНТЕНТ -->
<?php if (get_the_content()) : ?>
    <article class="work-content">
        <?php the_content(); ?>
    </article>
<?php endif; ?>

<!-- КОНЦЕПТ -->
<?php
$concept = get_post_meta($work_id, 'expert_concept', true);
if (!empty($concept)) : ?>
    <section class="work-section work-concept">
        <div class="section-header">
            <h2 class="section-title"><span class="section-icon">🎯</span> Концепт</h2>
            <div class="section-subtitle">Ключевая идея</div>
        </div>
        <div class="section-content">
            <div class="concept-text"><?php echo wpautop(wp_kses_post($concept)); ?></div>
        </div>
    </section>
<?php endif; ?>