<?php
/**
 * Общие секции для всех работ (галерея, аудио, документы и т.д.)
 * 
 * @var int $work_id
 * @var object $pods
 */

$work_id = get_the_ID();
$pods = pods('work', $work_id);
?>

<!-- 1. ЦИТАТА -->
<?php
$quote = get_post_meta($work_id, 'citate', true);
if (!empty($quote)) : ?>
<section class="work-section work-quote">
    <div class="section-header">
        <h2 class="section-title"><span class="section-icon">💡</span> Ключевая цитата</h2>
        <div class="section-subtitle">Суть исследования</div>
    </div>
    <div class="section-content">
        <blockquote class="work-quote-text"><?php echo esc_html($quote); ?></blockquote>
    </div>
</section>
<?php endif; ?>

<!-- 2. РАСШИРЕННОЕ ОПИСАНИЕ ПРОЕКТА -->
<?php
$description = get_post_meta($work_id, 'description', true);
if (!empty($description)) : ?>
<section class="work-section work-description">
    <div class="section-header">
        <h2 class="section-title"><span class="section-icon">📋</span> Описание проекта</h2>
        <div class="section-subtitle">Контекст и задачи</div>
    </div>
    <div class="section-content">
        <div class="description-text"><?php echo wpautop(wp_kses_post($description)); ?></div>
    </div>
</section>
<?php endif; ?>

<!-- 3. ЛАБОРАТОРИЯ (ПРОЦЕСС И ИНСАЙТЫ) -->
<?php
$lab_text = get_post_meta($work_id, 'text_labor', true);
if (!empty($lab_text)) : ?>
<section class="work-section work-laboratory">
    <div class="section-header">
        <h2 class="section-title"><span class="section-icon">🧪</span> Лаборатория</h2>
        <div class="section-subtitle">Процесс и инсайты</div>
    </div>
    <div class="section-content">
        <div class="laboratory-text"><?php echo wpautop(wp_kses_post($lab_text)); ?></div>
    </div>
</section>
<?php endif; ?>

<!-- 4. ГАЛЕРЕЯ -->
<?php
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

if (!empty($image_ids)) : 
    $gallery_title = get_post_meta($work_id, 'gallery_title', true) ?: 'Галерея проекта';
?>
<section class="work-section work-gallery">
    <div class="section-header">
        <h2 class="section-title"><span class="section-icon">🖼️</span> <?php echo esc_html($gallery_title); ?></h2>
        <div class="section-subtitle">Визуальные материалы <span class="image-count">(<?php echo count($image_ids); ?>)</span></div>
    </div>
    <div class="section-content">
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
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt); ?>" class="gallery-image" loading="lazy">
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
<?php endif; ?>

<!-- 4.5. ВИДЕОФАЙЛЫ (дополнительное видео, если основная форма не video) -->
<?php
// Получаем видеофайлы (если не были показаны в основном контенте)
$video_data = $pods->field('video_files');
$video_ids = array();

if (!empty($video_data) && is_array($video_data)) {
    foreach ($video_data as $item) {
        if (is_array($item) && isset($item['ID'])) {
            $video_ids[] = $item['ID'];
        } elseif (is_object($item) && isset($item->ID)) {
            $video_ids[] = $item->ID;
        } elseif (is_numeric($item)) {
            $video_ids[] = $item;
        }
    }
}

$video_ids = array_unique($video_ids);

// Проверяем, есть ли видео и не является ли видео основной формой
// (если основная форма video, видео уже показано в work-content-video.php)
$form_terms = get_the_terms($work_id, 'work_form');
$primary_form = 'text';
if ($form_terms && !is_wp_error($form_terms)) {
    $forms = wp_list_pluck($form_terms, 'slug');
    $primary_form = $forms[0];
}

$is_video_primary = ($primary_form === 'video');
?>

<?php if (!empty($video_ids) && !$is_video_primary) : ?>
<section class="work-section work-video">
    <div class="section-header">
        <h2 class="section-title">
            <span class="section-icon">🎬</span>
            Видео
        </h2>
        <div class="section-subtitle">
            Движущиеся изображения
            <span class="file-count">(<?php echo count($video_ids); ?>)</span>
        </div>
    </div>
    
    <div class="section-content">
        <div class="video-grid">
            <?php 
            foreach ($video_ids as $file_id) : 
                $file_url = wp_get_attachment_url($file_id);
                $file_type = get_post_mime_type($file_id);
                $thumbnail_id = get_post_thumbnail_id($file_id);
                $poster = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : '';
                $caption = wp_get_attachment_caption($file_id);
                $title = get_the_title($file_id);
            ?>
            <div class="video-item" data-video-id="<?php echo $file_id; ?>">
                <div class="video-item-inner">
                    <?php if ($file_url) : ?>
                        <?php if (strpos($file_type, 'video') !== false) : ?>
                            <video 
                                class="video-player" 
                                controls 
                                preload="metadata"
                                <?php echo $poster ? 'poster="' . esc_url($poster) . '"' : ''; ?>
                                width="100%" 
                                height="auto">
                                <source src="<?php echo esc_url($file_url); ?>" type="<?php echo esc_attr($file_type); ?>">
                                Ваш браузер не поддерживает видео.
                            </video>
                        <?php else : ?>
                            <div class="video-placeholder">
                                <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="file-link">
                                    <span class="file-icon">🎬</span>
                                    <span class="file-name"><?php echo esc_html($title); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($caption)) : ?>
                        <div class="video-caption">
                            <?php echo esc_html($caption); ?>
                        </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="video-error">
                            Файл не найден
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 5. АУДИОФАЙЛЫ -->
<?php
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

if (!empty($audio_ids)) : ?>
<section class="work-section work-audio">
    <div class="section-header">
        <h2 class="section-title"><span class="section-icon">🎵</span> Аудио</h2>
        <div class="section-subtitle">Звук и музыка <span class="file-count">(<?php echo count($audio_ids); ?>)</span></div>
    </div>
    <div class="section-content">
        <div class="audio-grid">
            <?php foreach ($audio_ids as $file_id) : 
                $file_url = wp_get_attachment_url($file_id);
                $file_type = get_post_mime_type($file_id);
                $title = get_the_title($file_id);
                $caption = wp_get_attachment_caption($file_id);
            ?>
                <div class="audio-item">
                    <div class="audio-player-wrapper">
                        <div class="audio-info">
                            <span class="audio-icon">🎵</span>
                            <span class="audio-title"><?php echo esc_html($title); ?></span>
                        </div>
                        <?php if ($file_url) : ?>
                            <audio class="audio-player" controls preload="none">
                                <source src="<?php echo esc_url($file_url); ?>" type="<?php echo esc_attr($file_type); ?>">
                            </audio>
                        <?php else : ?>
                            <div class="audio-error">Файл не найден</div>
                        <?php endif; ?>
                        <?php if (!empty($caption)) : ?>
                            <div class="audio-caption"><?php echo esc_html($caption); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 6. ДОКУМЕНТЫ -->
<?php
$document_data = $pods->field('document_files');
$document_ids = [];

if (!empty($document_data) && is_array($document_data)) {
    foreach ($document_data as $item) {
        if (is_array($item) && isset($item['ID'])) $document_ids[] = $item['ID'];
        elseif (is_object($item) && isset($item->ID)) $document_ids[] = $item->ID;
        elseif (is_numeric($item)) $document_ids[] = $item;
    }
}
$document_ids = array_unique($document_ids);

if (!empty($document_ids)) : ?>
<section class="work-section work-documents">
    <div class="section-header">
        <h2 class="section-title"><span class="section-icon">📄</span> Документы</h2>
        <div class="section-subtitle">PDF, тексты и материалы <span class="file-count">(<?php echo count($document_ids); ?>)</span></div>
    </div>
    <div class="section-content">
        <div class="documents-grid">
            <?php foreach ($document_ids as $file_id) : 
                $file_url = wp_get_attachment_url($file_id);
                $file_type = get_post_mime_type($file_id);
                $file_path = get_attached_file($file_id);
                $file_size = file_exists($file_path) ? filesize($file_path) : 0;
                $file_size_formatted = $file_size ? size_format($file_size, 1) : '0 KB';
                $title = get_the_title($file_id);
                
                $icon = '📄';
                if (strpos($file_type, 'pdf') !== false) $icon = '📕';
                elseif (strpos($file_type, 'word') !== false || strpos($file_type, 'document') !== false) $icon = '📘';
                elseif (strpos($file_type, 'text') !== false) $icon = '📃';
                elseif (strpos($file_type, 'presentation') !== false) $icon = '📊';
                elseif (strpos($file_type, 'image') !== false) $icon = '🖼️';
                elseif (strpos($file_type, 'zip') !== false) $icon = '🗜️';
            ?>
                <div class="document-item">
                    <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="document-link">
                        <span class="document-icon"><?php echo $icon; ?></span>
                        <div class="document-info">
                            <span class="document-title"><?php echo esc_html($title); ?></span>
                            <span class="document-meta">
                                <?php $ext = pathinfo($file_url, PATHINFO_EXTENSION); echo strtoupper($ext); ?> • <?php echo esc_html($file_size_formatted); ?>
                            </span>
                        </div>
                        <span class="download-icon">⬇️</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>