<?php
/**
 * Контент для работы с формой "Текст"
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// --- ПОЛУЧАЕМ ОБЛОЖКУ ---
$main_preview_id = $pods->field('main_preview');
$cover_id = null;

if (!empty($main_preview_id) && is_numeric($main_preview_id)) {
    $cover_id = $main_preview_id;
} elseif (has_post_thumbnail()) {
    $cover_id = get_post_thumbnail_id();
}

// --- 1. ОБЛОЖКА (если есть) ---
if ($cover_id) : 
    $cover_url = wp_get_attachment_image_url($cover_id, 'large');
    $full_url = wp_get_attachment_image_url($cover_id, 'full');
    $alt = get_post_meta($cover_id, '_wp_attachment_image_alt', true) ?: get_the_title();
    $caption = wp_get_attachment_caption($cover_id);
?>
<section class="work-section work-cover">
    <div class="cover-container">
        <div class="cover-image-wrapper">
            <a href="<?php echo esc_url($full_url); ?>" data-lightbox="work-cover" data-title="<?php echo esc_attr($caption ?: get_the_title()); ?>">
                <img src="<?php echo esc_url($cover_url); ?>" 
                     alt="<?php echo esc_attr($alt); ?>" 
                     class="cover-image"
                     loading="eager">
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 2. КРАТКОЕ ОПИСАНИЕ (теглайн) -->
<?php
$short_description = get_post_meta($work_id, 'description', true);
if (!empty($short_description)) : ?>
    <section class="work-section work-teaser">
        <div class="section-content">
            <p class="teaser-text"><?php echo esc_html($short_description); ?></p>
        </div>
    </section>
<?php endif; ?>

<!-- 3. ОСНОВНОЙ ТЕКСТ -->
<?php if (get_the_content()) : ?>
    <article class="work-content">
        <?php the_content(); ?>
    </article>
<?php endif; ?>

<!-- 4. КОНЦЕПТ -->
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