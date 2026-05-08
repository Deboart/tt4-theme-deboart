<?php
/**
 * Контент для работы с формой "Видео"
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// Получаем видеофайлы
$video_data = $pods->field('video_files');
$video_ids = [];

if (!empty($video_data) && is_array($video_data)) {
    foreach ($video_data as $item) {
        if (is_array($item) && isset($item['ID'])) $video_ids[] = $item['ID'];
        elseif (is_object($item) && isset($item->ID)) $video_ids[] = $item->ID;
        elseif (is_numeric($item)) $video_ids[] = $item;
    }
}
?>

<!-- ВИДЕО-ПЛЕЕРЫ (поддержка нескольких видео) -->
<?php if (!empty($video_ids)) : ?>
<section class="work-section work-video-primary">
    <div class="video-primary-grid">
        <?php foreach ($video_ids as $file_id) :
            $file_url = wp_get_attachment_url($file_id);
            $file_type = get_post_mime_type($file_id);
            $poster_id = get_post_thumbnail_id($file_id);
            $poster = $poster_id ? wp_get_attachment_image_url($poster_id, 'large') : '';
            $title = get_the_title($file_id);
            $caption = wp_get_attachment_caption($file_id);
        ?>
            <div class="video-primary-wrapper">
                <video class="video-primary-player" 
                       controls 
                       preload="metadata"
                       poster="<?php echo esc_url($poster); ?>"
                       width="100%"
                       title="<?php echo esc_attr($title); ?>"
                       playsinline>
                    <source src="<?php echo esc_url($file_url); ?>" type="<?php echo esc_attr($file_type); ?>">
                    Ваш браузер не поддерживает видео.
                </video>
                <?php if ($caption) : ?>
                    <p class="video-caption"><?php echo esc_html($caption); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- КРАТКОЕ ОПИСАНИЕ / ТЕГЛАЙН -->
<?php /*
$short_description = get_post_meta($work_id, 'description', true);
if (!empty($short_description)) : ?>
    <section class="work-section work-teaser">
        <div class="section-content">
            <p class="teaser-text"><?php echo esc_html(wp_trim_words($short_description, 20, '...')); ?></p>
        </div>
    </section>
<?php  endif; */?>

<!-- ОСНОВНОЙ КОНТЕНТ (если есть) -->
<?php if (get_the_content()) : ?>
    <article class="work-content">
        <?php the_content(); ?>
    </article>
<?php endif; ?>

<!-- КОНЦЕПТ (вынесем в общие? пока оставим здесь, но можно потом перенести) -->
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