<?php
/**
 * Контент для работы с формой "Изображение"
 * Акцент — на галерее или главном изображении
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// Получаем галерею
$gallery_data = $pods->field('image_gallery');
$image_ids = [];

if (!empty($gallery_data) && is_array($gallery_data)) {
    foreach ($gallery_data as $item) {
        if (is_array($item) && isset($item['ID'])) {
            $image_ids[] = $item['ID'];
        } elseif (is_object($item) && isset($item->ID)) {
            $image_ids[] = $item->ID;
        } elseif (is_numeric($item)) {
            $image_ids[] = $item;
        }
    }
}
$image_ids = array_unique($image_ids);
?>

<!-- ГЛАВНОЕ ИЗОБРАЖЕНИЕ / ГАЛЕРЕЯ (акцент) -->
<?php if (!empty($image_ids)) : ?>
    <section class="work-section work-image-primary">
        <div class="image-primary-container">
            <?php if (count($image_ids) === 1) : 
                $image_id = $image_ids[0];
                $full_url = wp_get_attachment_image_url($image_id, 'full');
                $large_url = wp_get_attachment_image_url($image_id, 'large');
                $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                $caption = wp_get_attachment_caption($image_id);
            ?>
                <div class="single-image-wrapper">
                    <a href="<?php echo esc_url($full_url); ?>" data-lightbox="work-primary" data-title="<?php echo esc_attr($caption ?: get_the_title()); ?>">
                        <img src="<?php echo esc_url($large_url); ?>" 
                             alt="<?php echo esc_attr($alt); ?>" 
                             class="primary-image">
                    </a>
                    <?php if ($caption) : ?>
                        <p class="image-caption"><?php echo esc_html($caption); ?></p>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="gallery-primary-grid">
                    <div class="gallery-grid">
                        <?php $counter = 1;
                        foreach ($image_ids as $image_id) : 
                            $image_url = wp_get_attachment_image_url($image_id, 'large');
                            if ($image_url) : 
                                $full_url = wp_get_attachment_image_url($image_id, 'full');
                                $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: 'Изображение ' . $counter;
                                $caption = wp_get_attachment_caption($image_id);
                        ?>
                            <div class="gallery-item">
                                <div class="gallery-item-inner">
                                    <a href="<?php echo esc_url($full_url); ?>" 
                                       class="gallery-image-link"
                                       data-lightbox="work-gallery"
                                       data-title="<?php echo $caption ? esc_attr($caption) : 'Изображение ' . $counter; ?>">
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                             alt="<?php echo esc_attr($alt); ?>" 
                                             class="gallery-image"
                                             loading="lazy">
                                        <div class="image-overlay"><span class="zoom-icon">🔍</span></div>
                                    </a>
                                    <?php if (!empty($caption)) : ?>
                                        <div class="image-caption"><?php echo esc_html($caption); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php 
                                $counter++;
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php elseif (has_post_thumbnail()) : ?>
    <section class="work-section work-image-primary">
        <div class="single-image-wrapper">
            <?php the_post_thumbnail('large', array('class' => 'primary-image')); ?>
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