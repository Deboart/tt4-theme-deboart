<?php
/**
 * Контент для работы с формой "Объект"
 * Акцент — на галерее, 3D-вьювере (если есть) или технических деталях
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);

// Получаем галерею (для объекта это важно)
$gallery_data = $pods->field('image_gallery');
$image_ids = [];

if (!empty($gallery_data) && is_array($gallery_data)) {
    foreach ($gallery_data as $item) {
        if (is_array($item) && isset($item['ID'])) $image_ids[] = $item['ID'];
        elseif (is_object($item) && isset($item->ID)) $image_ids[] = $item->ID;
        elseif (is_numeric($item)) $image_ids[] = $item;
    }
}
$image_ids = array_unique($image_ids);

// Поле для 3D-модели (если добавишь позже)
$model_url = get_post_meta($work_id, 'model_3d_url', true);
?>

<!-- ГАЛЕРЕЯ (основной акцент для объекта) -->
<?php if (!empty($image_ids)) : ?>
    <section class="work-section work-object-gallery">
        <div class="object-gallery-container">
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
                               data-lightbox="object-gallery"
                               data-title="<?php echo $caption ? esc_attr($caption) : 'Вид ' . $counter; ?>">
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
    </section>
<?php elseif (has_post_thumbnail()) : ?>
    <section class="work-section work-object-primary">
        <div class="single-image-wrapper">
            <?php the_post_thumbnail('large', array('class' => 'primary-image')); ?>
        </div>
    </section>
<?php endif; ?>

<!-- 3D-МОДЕЛЬ (если есть) -->
<?php if (!empty($model_url)) : ?>
    <section class="work-section work-3d-model">
        <div class="section-header">
            <h2 class="section-title"><span class="section-icon">🧊</span> 3D-модель</h2>
            <div class="section-subtitle">Интерактивный просмотр</div>
        </div>
        <div class="section-content">
            <div class="model-wrapper">
                <?php 
                // Поддержка разных сервисов
                if (strpos($model_url, 'sketchfab.com') !== false) {
                    // Sketchfab embed
                    $embed_url = str_replace('watch', 'embed', $model_url);
                    ?>
                    <iframe src="<?php echo esc_url($embed_url); ?>" 
                            frameborder="0"
                            allow="autoplay; fullscreen; vr"
                            allowfullscreen>
                    </iframe>
                    <?php
                } elseif (strpos($model_url, 'clara.io') !== false) {
                    // Clara.io embed
                    ?>
                    <iframe src="<?php echo esc_url($model_url); ?>" frameborder="0" allowfullscreen></iframe>
                    <?php
                } else {
                    // Обычная ссылка
                    ?>
                    <a href="<?php echo esc_url($model_url); ?>" class="model-link" target="_blank" rel="noopener noreferrer">
                        <span class="model-link-icon">🔗</span>
                        Открыть 3D-модель →
                    </a>
                    <?php
                }
                ?>
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