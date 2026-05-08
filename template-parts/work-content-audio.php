<?php
/**
 * Контент для работы с формой "Аудио"
 * Акцент — на аудиоплеере с обложкой (как музыкальный плеер)
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// Получаем аудиофайлы
$audio_data = $pods->field('audio_files');
$audio_ids = [];

if (!empty($audio_data) && is_array($audio_data)) {
    foreach ($audio_data as $item) {
        if (is_array($item) && isset($item['ID'])) $audio_ids[] = $item['ID'];
        elseif (is_object($item) && isset($item->ID)) $audio_ids[] = $item->ID;
        elseif (is_numeric($item)) $audio_ids[] = $item;
    }
}
$audio_ids = array_unique($audio_ids);

// Получаем обложку (первое изображение из галереи или главное)
$cover_id = null;
$gallery_data = $pods->field('image_gallery');
if (!empty($gallery_data) && is_array($gallery_data)) {
    foreach ($gallery_data as $item) {
        if (is_array($item) && isset($item['ID'])) {
            $cover_id = $item['ID'];
            break;
        } elseif (is_object($item) && isset($item->ID)) {
            $cover_id = $item->ID;
            break;
        } elseif (is_numeric($item)) {
            $cover_id = $item;
            break;
        }
    }
}
if (!$cover_id && has_post_thumbnail()) {
    $cover_id = get_post_thumbnail_id();
}

// Если обложки нет — используем логотип DEBOART
$cover_url = null;
if ($cover_id) {
    $cover_url = wp_get_attachment_image_url($cover_id, 'large');
    $cover_thumbnail = wp_get_attachment_image_url($cover_id, 'medium');
} else {
    // Путь к логотипу DEBOART (замени на актуальный)
    $cover_url = get_stylesheet_directory_uri() . '/assets/images/deboart-logo-cover.png';
    $cover_thumbnail = get_stylesheet_directory_uri() . '/assets/images/deboart-logo-cover.png';
}
?>

<!-- АУДИО-ПЛЕЕР С ОБЛОЖКОЙ (основной акцент) -->
<?php if (!empty($audio_ids)) : ?>
<section class="work-section work-audio-primary">
    <div class="audio-card">
        <?php if ($cover_url) : ?>
            <div class="audio-card__cover">
                <img src="<?php echo esc_url($cover_url); ?>" 
                     alt="<?php echo esc_attr(get_the_title()); ?>"
                     class="audio-card__cover-image">
            </div>
        <?php else : ?>
            <div class="audio-card__cover audio-card__cover--placeholder">
                <span class="audio-card__placeholder-icon">🎵</span>
            </div>
        <?php endif; ?>
        
        <div class="audio-card__info">
            <h3 class="audio-card__title"><?php the_title(); ?></h3>
            <?php 
            $short_description = get_post_meta($work_id, 'short_description', true);
            if (!empty($short_description)) : ?>
                <p class="audio-card__artist"><?php echo esc_html($short_description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="audio-card__player">
            <?php foreach ($audio_ids as $file_id) :
                $file_url = wp_get_attachment_url($file_id);
                $file_type = get_post_mime_type($file_id);
                $title = get_the_title($file_id);
                $caption = wp_get_attachment_caption($file_id);
            ?>
                <audio class="audio-card__player-element" controls preload="metadata">
                    <source src="<?php echo esc_url($file_url); ?>" type="<?php echo esc_attr($file_type); ?>">
                    Ваш браузер не поддерживает аудио.
                </audio>
                <?php if (!empty($caption)) : ?>
                    <p class="audio-card__caption"><?php echo esc_html($caption); ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ОСНОВНОЙ КОНТЕНТ (если есть) -->
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