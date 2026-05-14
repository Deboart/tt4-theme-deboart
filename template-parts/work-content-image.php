<?php
/**
 * Контент для работы с формой "Изображение"
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// Получаем обложку из поля main_preview
$main_preview_id = $pods->field('main_preview');
$cover_id = null;

if (!empty($main_preview_id) && is_numeric($main_preview_id)) {
    $cover_id = $main_preview_id;
} elseif (has_post_thumbnail()) {
    $cover_id = get_post_thumbnail_id();
}

// Получаем все изображения из галереи
$gallery_data = $pods->field('image_gallery');
$all_image_ids = [];

if (!empty($gallery_data) && is_array($gallery_data)) {
    foreach ($gallery_data as $item) {
        if (is_array($item) && isset($item['ID'])) {
            $all_image_ids[] = $item['ID'];
        } elseif (is_object($item) && isset($item->ID)) {
            $all_image_ids[] = $item->ID;
        } elseif (is_numeric($item)) {
            $all_image_ids[] = $item;
        }
    }
}
$all_image_ids = array_unique($all_image_ids);

// Остальные изображения (исключаем обложку)
$other_ids = array();
if (!empty($all_image_ids) && $cover_id) {
    $other_ids = array_diff($all_image_ids, array($cover_id));
} elseif (!empty($all_image_ids)) {
    // Если обложки нет — все изображения считаем дополнительными
    $other_ids = $all_image_ids;
}
?>

<!-- 1. ОБЛОЖКА (крупно) -->
<?php if ($cover_id) : 
    $cover_url = wp_get_attachment_image_url($cover_id, 'large');
    $full_url = wp_get_attachment_image_url($cover_id, 'full');
    $alt = get_post_meta($cover_id, '_wp_attachment_image_alt', true) ?: get_the_title();
    $caption = wp_get_attachment_caption($cover_id);
?>
<section class="work-section work-cover">
    <div class="cover-container">
        <div class="cover-image-wrapper">
            <a href="<?php echo esc_url($full_url); ?>" data-lightbox="work-gallery" data-title="<?php echo esc_attr($caption ?: get_the_title()); ?>">
                <img src="<?php echo esc_url($cover_url); ?>" 
                     alt="<?php echo esc_attr($alt); ?>" 
                     class="cover-image">
            </a>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- 2. ВСЕ ОСТАЛЬНЫЕ ИЗОБРАЖЕНИЯ (сетка, без заголовка) -->
<?php if (!empty($other_ids)) : ?>
<section class="work-section work-images-grid">
    <div class="images-grid-container">
        <div class="images-grid">
            <?php $counter = 1;
            foreach ($other_ids as $image_id) : 
                $image_url = wp_get_attachment_image_url($image_id, 'medium');
                $full_url = wp_get_attachment_image_url($image_id, 'full');
                $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: 'Изображение ' . $counter;
                $caption = wp_get_attachment_caption($image_id);
            ?>
                <div class="grid-item">
                    <a href="<?php echo esc_url($full_url); ?>" 
                       data-lightbox="work-gallery"
                       data-title="<?php echo $caption ? esc_attr($caption) : ''; ?>">
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr($alt); ?>" 
                             class="grid-image"
                             loading="lazy">
                    </a>
                </div>
            <?php 
                $counter++;
            endforeach; 
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 3. КРАТКОЕ ОПИСАНИЕ / ТЕГЛАЙН -->
<?php
$short_description = get_post_meta($work_id, 'description', true);
if (!empty($short_description)) : ?>
    <section class="work-section work-teaser">
        <div class="section-content">
            <p class="teaser-text"><?php echo esc_html($short_description); ?></p>
        </div>
    </section>
<?php endif; ?>

<!-- 4. ОСНОВНОЙ КОНТЕНТ -->
<?php if (get_the_content()) : ?>
    <article class="work-content">
        <?php the_content(); ?>
    </article>
<?php endif; ?>

<!-- 5. КОНЦЕПТ -->
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