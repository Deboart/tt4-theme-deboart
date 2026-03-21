<?php
/**
 * Single Work Template for DEBOART
 * Template Post Type: work
 * Версия: 2.0 (лабораторный стиль)
 */
 
// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';


// Получаем ID работы и объект Pods для всех секций
$work_id = get_the_ID();
$pods = pods('work', $work_id); // Добавь эту строку
?>

<!-- Основной контейнер -->
<main class="deboart-work-main">
    
    <!-- Минимальная верхняя навигация -->
    <nav class="work-top-nav">
        <a href="<?php echo home_url('/'); ?>" class="nav-home">
            <span class="nav-icon">←</span>
            <span>DEBOART</span>
        </a>
        
        <div class="nav-actions">
            <a href="<?php echo get_post_type_archive_link('work'); ?>" class="nav-archive">
                <span>📚</span>
                <span>Все исследования</span>
            </a>
        </div>
    </nav>

    <div class="deboart-work-container">

        <!-- Хлебные крошки -->
        <nav class="work-breadcrumbs">
            <a href="<?php echo home_url(); ?>">Главная</a>
            <span class="separator">/</span>
            <a href="<?php echo get_post_type_archive_link('work'); ?>">Исследования</a>
            <span class="separator">/</span>
            <span class="current"><?php the_title(); ?></span>
        </nav>

        <!-- Шапка работы -->
        <header class="work-header">
            <!-- Мета-данные сверху (год и статус) -->
            <div class="work-meta-top">
                <?php 
                $work_date = get_post_meta(get_the_ID(), 'work_date', true);
                if (!empty($work_date)) :
                    $year = $work_date;
                    if (strtotime($work_date)) $year = date('Y', strtotime($work_date));
                    elseif (preg_match('/\d{4}/', $work_date, $matches)) $year = $matches[0];
                ?>
                <div class="work-year">
                    <span class="icon">⏳</span>
                    <span class="text"><?php echo esc_html($year); ?></span>
                </div>
                <?php endif; ?>

                <?php if (get_post_status() === 'private') : ?>
                <div class="work-status private">
                    🔒 Приватное
                </div>
                <?php endif; ?>
            </div>

            <!-- Заголовок работы -->
            <h1 class="work-title"><?php the_title(); ?></h1>

            <!-- Парадигма: Форма и Содержание (компактно) -->
            <div class="work-taxonomies">
                <?php
                // Используем те же функции иконок что и в архиве
                if (!function_exists('deboart_get_form_icon')) {
                    function deboart_get_form_icon($slug) {
                        $icons = array(
                            'text'   => '📖',
                            'image'  => '🖼️',
                            'video'  => '🎬',
                            'audio'  => '🎵',
                            'web'    => '🌐',
                            'object' => '✨'
                        );
                        return isset($icons[$slug]) ? $icons[$slug] : '🎨';
                    }
                }

                if (!function_exists('deboart_get_feeling_icon')) {
                    function deboart_get_feeling_icon($slug) {
                        $icons = array(
                            'tishina' => '😌',
                            'energy'  => '⚡',
                            'thought' => '🤔',
                            'drama'   => '🎭',
                            'chaos'   => '🌀',
                            'memory'  => '🕰️'
                        );
                        return isset($icons[$slug]) ? $icons[$slug] : '💭';
                    }
                }


                /**
                 * Вспомогательная функция для создания ссылки на архив с фильтром.
                 *
                 * @param string $taxonomy Слаг таксономии ('work_form' или 'work_feeling').
                 * @param string $term_slug Слаг термина.
                 * @return string URL отфильтрованного архива.
                 */
                function deboart_get_filtered_archive_url($taxonomy, $term_slug) {
                    $base_url = get_post_type_archive_link('work');
                    
                    $args = array(
                        'search' => '', // Пустой поиск, чтобы параметр был в URL
                    );
                    
                    // Добавляем параметр в зависимости от таксономии
                    if ($taxonomy === 'work_form') {
                        $args['form'] = array($term_slug);
                    } elseif ($taxonomy === 'work_feeling') {
                        $args['feeling'] = array($term_slug);
                    }
                    
                    return add_query_arg($args, $base_url);
                }

                // Функция для вывода таксономий (обновленная)
                function deboart_show_taxonomy($taxonomy, $label) {
                    $terms = get_the_terms(get_the_ID(), $taxonomy);
                    if ($terms && !is_wp_error($terms)) : ?>
                        <div class="taxonomy-group">
                            <span class="taxonomy-label"><?php echo $label; ?>:</span>
                            <div class="taxonomy-items">
                                <?php foreach ($terms as $term) : 
                                    // Получаем иконку
                                    $icon = '';
                                    if ($taxonomy == 'work_form') {
                                        $icon = deboart_get_form_icon($term->slug);
                                    } elseif ($taxonomy == 'work_feeling') {
                                        $icon = deboart_get_feeling_icon($term->slug);
                                    }
                                    
                                    // Получаем правильный URL для фильтрации
                                    $filter_url = deboart_get_filtered_archive_url($taxonomy, $term->slug);
                                ?>
                                    <a href="<?php echo esc_url($filter_url); ?>" 
                                       class="taxonomy-item">
                                        <span class="taxonomy-icon"><?php echo $icon; ?></span>
                                        <span class="taxonomy-name"><?php echo esc_html($term->name); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif;
                }

                // Вывод таксономий
                deboart_show_taxonomy('work_form', 'Форма');
                deboart_show_taxonomy('work_feeling', 'Содержание');
                ?>
            </div>


        </header>

        <!-- Главное изображение -->
        <?php if (has_post_thumbnail()) : ?>
        <div class="work-featured-image">
            <?php the_post_thumbnail('full', array(
                'class' => 'featured-img',
                'loading' => 'eager'
            )); ?>
        </div>
        <?php endif; ?>

        <!-- Основной контент -->
        <article class="work-content">
            <?php the_content(); ?>
        </article>


        <!-- Основной контент (уже есть) -->
        <article class="work-content">
            <?php the_content(); ?>
        </article>

        <!-- 1. ЦИТАТА (бывшая citate) -->
        <?php
        $quote = get_post_meta($work_id, 'citate', true);
        if (!empty($quote)) : ?>
        <section class="work-section work-quote">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">💡</span>
                    Ключевая цитата
                </h2>
                <div class="section-subtitle">Суть исследования</div>
            </div>
            <div class="section-content">
                <blockquote class="work-quote-text">
                    <?php echo esc_html($quote); ?>
                </blockquote>
            </div>
        </section>
        <?php endif; ?>

        <!-- 2. ОПИСАНИЕ ПРОЕКТА (расширенное описание) -->
        <?php
        $description = get_post_meta(get_the_ID(), 'description', true);
        if (!empty($description)) : ?>
        <section class="work-section work-description">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">📋</span>
                    Описание проекта
                </h2>
                <div class="section-subtitle">Контекст и задачи</div>
            </div>
            <div class="section-content">
                <div class="description-text">
                    <?php echo wpautop(wp_kses_post($description)); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- 3. КОНЦЕПТ (expert_concept) -->
        <?php
        $concept = get_post_meta($work_id, 'expert_concept', true);
        if (!empty($concept)) : ?>
        <section class="work-section work-concept">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">🎯</span>
                    Концепт
                </h2>
                <div class="section-subtitle">Ключевая идея</div>
            </div>
            <div class="section-content">
                <div class="concept-text">
                    <?php echo wpautop(wp_kses_post($concept)); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- 4. ТЕКСТ ДЛЯ ЛАБОРАТОРИИ (text_labor) -->
        <?php
        $lab_text = get_post_meta($work_id, 'text_labor', true);
        if (!empty($lab_text)) : ?>
        <section class="work-section work-laboratory">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">🧪</span>
                    Лаборатория
                </h2>
                <div class="section-subtitle">Процесс и инсайты</div>
            </div>
            <div class="section-content">
                <div class="laboratory-text">
                    <?php echo wpautop(wp_kses_post($lab_text)); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- 5. ВПЕЧАТЛЕНИЕ ОТ ИИ (opinion_AI) -->
        <?php
        $opinion_ai = get_post_meta(get_the_ID(), 'opinion_AI', true);
        if (!empty($opinion_ai)) : ?>
        <section class="work-section work-ai-opinion">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">🤖</span>
                    Взгляд со стороны
                </h2>
                <div class="section-subtitle">Анализ и рефлексия ИИ</div>
            </div>
            <div class="section-content">
                <div class="ai-opinion-text">
                    <?php echo wpautop(wp_kses_post($opinion_ai)); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- ЧТО СДЕЛАТЬ (chto_sdelat) - если нужно оставить, добавить сюда же -->
        <?php
        $chto_sdelat = get_post_meta(get_the_ID(), 'chto_sdelat', true);
        if (!empty($chto_sdelat)) : ?>
        <section class="work-section work-todo">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">📋</span>
                    Что сделать
                </h2>
                <div class="section-subtitle">Задачи и следующие шаги</div>
            </div>
            <div class="section-content">
                <div class="todo-text">
                    <?php echo wpautop(wp_kses_post($chto_sdelat)); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        
        <!-- ГАЛЕРЕЯ РАБОТЫ (ИСПРАВЛЕННАЯ) -->
        <?php
        $work_id = get_the_ID();
        
        // Получаем данные через Pods
        $pods = pods('work', $work_id);
        $gallery_data = $pods->field('image_gallery');
        
        $image_ids = array();
        
        if (!empty($gallery_data) && is_array($gallery_data)) {
            foreach ($gallery_data as $item) {
                // Pods возвращает массив с ключом ID
                if (is_array($item) && isset($item['ID'])) {
                    $image_ids[] = $item['ID'];
                }
                // На всякий случай, если это объект
                elseif (is_object($item) && isset($item->ID)) {
                    $image_ids[] = $item->ID;
                }
                // Если вдруг просто ID
                elseif (is_numeric($item)) {
                    $image_ids[] = $item;
                }
            }
        }
        
        // Убираем дубликаты
        $image_ids = array_unique($image_ids);
        
        // Для отладки
        echo '<!-- Extracted image IDs: ' . print_r($image_ids, true) . ' -->';
        ?>

        <?php if (!empty($image_ids)) : 
            $gallery_title = get_post_meta($work_id, 'gallery_title', true) ?: 'Галерея проекта';
        ?>
            <section class="work-section work-gallery">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">🖼️</span>
                        <?php echo esc_html($gallery_title); ?>
                    </h2>
                    <div class="section-subtitle">
                        Визуальные материалы
                        <span class="image-count">(<?php echo count($image_ids); ?>)</span>
                    </div>
                </div>
                
                <div class="section-content">
                    <div class="gallery-grid">
                        <?php 
                        $counter = 1;
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
                                   data-title="<?php if($caption) echo esc_attr($caption); else echo 'Изображение ' . $counter; ?>">
                                    <img src="<?php echo esc_url($image_url); ?>" 
                                         alt="<?php echo esc_attr($alt); ?>"
                                         class="gallery-image"
                                         loading="lazy">
                                    <div class="image-overlay">
                                        <span class="zoom-icon">🔍</span>
                                    </div>
                                </a>
                                
                                <?php if (!empty($caption)) : ?>
                                <div class="image-caption">
                                    <?php echo esc_html($caption); ?>
                                </div>
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

         <!-- ВИДЕОФАЙЛЫ -->
        <?php
        // Получаем данные через Pods
        $pods = pods('work', $work_id);
        $video_data = $pods->field('video_files');
        
        $video_ids = array();
        
        if (!empty($video_data) && is_array($video_data)) {
            foreach ($video_data as $item) {
                // Pods возвращает массив с ключом ID
                if (is_array($item) && isset($item['ID'])) {
                    $video_ids[] = $item['ID'];
                }
                // На всякий случай, если это объект
                elseif (is_object($item) && isset($item->ID)) {
                    $video_ids[] = $item->ID;
                }
                // Если вдруг просто ID
                elseif (is_numeric($item)) {
                    $video_ids[] = $item;
                }
            }
        }
        
        // Убираем дубликаты
        $video_ids = array_unique($video_ids);
        
        // Для отладки (можно удалить позже)
        echo '<!-- Video IDs: ' . print_r($video_ids, true) . ' -->';

        if (!empty($video_ids)) : 
        ?>
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

        <!-- АУДИОФАЙЛЫ -->
        <?php
        // Получаем данные через Pods
        $audio_data = $pods->field('audio_files');
        
        $audio_ids = array();
        
        if (!empty($audio_data) && is_array($audio_data)) {
            foreach ($audio_data as $item) {
                if (is_array($item) && isset($item['ID'])) {
                    $audio_ids[] = $item['ID'];
                } elseif (is_object($item) && isset($item->ID)) {
                    $audio_ids[] = $item->ID;
                } elseif (is_numeric($item)) {
                    $audio_ids[] = $item;
                }
            }
        }
        
        $audio_ids = array_unique($audio_ids);
        
        // Для отладки
        echo '<!-- Audio IDs: ' . print_r($audio_ids, true) . ' -->';

        if (!empty($audio_ids)) : 
            ?>
            <section class="work-section work-audio">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">🎵</span>
                        Аудио
                    </h2>
                    <div class="section-subtitle">
                        Звук и музыка
                        <span class="file-count">(<?php echo count($audio_ids); ?>)</span>
                    </div>
                </div>
                
                <div class="section-content">
                    <div class="audio-grid">
                        <?php 
                        foreach ($audio_ids as $file_id) : 
                            $file_url = wp_get_attachment_url($file_id);
                            $file_type = get_post_mime_type($file_id);
                            $title = get_the_title($file_id);
                            $caption = wp_get_attachment_caption($file_id);
                        ?>
                        <div class="audio-item" data-audio-id="<?php echo $file_id; ?>">
                            <div class="audio-player-wrapper">
                                <div class="audio-info">
                                    <span class="audio-icon">🎵</span>
                                    <span class="audio-title"><?php echo esc_html($title); ?></span>
                                </div>
                                <?php if ($file_url) : ?>
                                <audio class="audio-player" controls preload="none">
                                    <source src="<?php echo esc_url($file_url); ?>" type="<?php echo esc_attr($file_type); ?>">
                                    Ваш браузер не поддерживает аудио.
                                </audio>
                                <?php else : ?>
                                <div class="audio-error">Файл не найден</div>
                                <?php endif; ?>
                                
                                <?php if (!empty($caption)) : ?>
                                <div class="audio-caption">
                                    <?php echo esc_html($caption); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- ДОКУМЕНТЫ -->
        <?php
        // Получаем данные через Pods
        $document_data = $pods->field('document_files');
        
        $document_ids = array();
        
        if (!empty($document_data) && is_array($document_data)) {
            foreach ($document_data as $item) {
                if (is_array($item) && isset($item['ID'])) {
                    $document_ids[] = $item['ID'];
                } elseif (is_object($item) && isset($item->ID)) {
                    $document_ids[] = $item->ID;
                } elseif (is_numeric($item)) {
                    $document_ids[] = $item;
                }
            }
        }
        
        $document_ids = array_unique($document_ids);
        
        // Для отладки
        echo '<!-- Document IDs: ' . print_r($document_ids, true) . ' -->';

        if (!empty($document_ids)) : 
            ?>
            <section class="work-section work-documents">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">📄</span>
                        Документы
                    </h2>
                    <div class="section-subtitle">
                        PDF, тексты и материалы
                        <span class="file-count">(<?php echo count($document_ids); ?>)</span>
                    </div>
                </div>
                
                <div class="section-content">
                    <div class="documents-grid">
                        <?php 
                        foreach ($document_ids as $file_id) : 
                            $file_url = wp_get_attachment_url($file_id);
                            $file_type = get_post_mime_type($file_id);
                            $file_path = get_attached_file($file_id);
                            $file_size = file_exists($file_path) ? filesize($file_path) : 0;
                            $file_size_formatted = $file_size ? size_format($file_size, 1) : '0 KB';
                            $title = get_the_title($file_id);
                            
                            // Определяем иконку по типу файла
                            $icon = '📄';
                            if (strpos($file_type, 'pdf') !== false) {
                                $icon = '📕';
                            } elseif (strpos($file_type, 'word') !== false || strpos($file_type, 'document') !== false) {
                                $icon = '📘';
                            } elseif (strpos($file_type, 'text') !== false) {
                                $icon = '📃';
                            } elseif (strpos($file_type, 'presentation') !== false) {
                                $icon = '📊';
                            } elseif (strpos($file_type, 'image') !== false) {
                                $icon = '🖼️';
                            } elseif (strpos($file_type, 'zip') !== false || strpos($file_type, 'archive') !== false) {
                                $icon = '🗜️';
                            }
                        ?>
                        <div class="document-item" data-document-id="<?php echo $file_id; ?>">
                            <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="document-link">
                                <span class="document-icon"><?php echo $icon; ?></span>
                                <div class="document-info">
                                    <span class="document-title"><?php echo esc_html($title); ?></span>
                                    <span class="document-meta">
                                        <?php 
                                        $file_extension = pathinfo($file_url, PATHINFO_EXTENSION);
                                        echo strtoupper($file_extension); ?> • <?php echo esc_html($file_size_formatted); ?>
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
        

		

        <!-- Технические детали -->
        <?php
        // Собираем все таксономии работы
        $taxonomies_to_show = array(
            'work_technique' => array('icon' => '🎨', 'label' => 'Техника'),
            'place' => array('icon' => '📍', 'label' => 'Место'),
            'nastroy' => array('icon' => '🎭', 'label' => 'Настрой'),
            'collaboration' => array('icon' => '👥', 'label' => 'Соавторство'),
            'work_collection' => array('icon' => '📚', 'label' => 'Коллекция'),
        );

        $has_taxonomies = false;
        foreach ($taxonomies_to_show as $tax => $data) {
            $terms = get_the_terms($work_id, $tax);
            if ($terms && !is_wp_error($terms)) {
                $has_taxonomies = true;
                break;
            }
        }

        // Проверяем дополнительные поля
        $additional_fields = array(
            'type_work' => array('icon' => '📋', 'label' => 'Тип проекта'),
            'stoimost' => array('icon' => '💰', 'label' => 'Стоимость'),
            'otziv' => array('icon' => '💬', 'label' => 'Отзыв'),
        );

        $has_fields = false;
        foreach ($additional_fields as $field => $data) {
            $value = get_post_meta($work_id, $field, true);
            if (!empty($value)) {
                $has_fields = true;
                break;
            }
        }

        if ($has_taxonomies || $has_fields) : 
        ?>
        <section class="work-section work-technical">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">⚙️</span>
                    Детали проекта
                </h2>
                <div class="section-subtitle">Метаданные и классификация</div>
            </div>
            
            <div class="section-content">
                <div class="technical-grid">
                    
                    <!-- Таксономии -->
                    <?php foreach ($taxonomies_to_show as $tax => $data) : 
                        $terms = get_the_terms($work_id, $tax);
                        if ($terms && !is_wp_error($terms)) : 
                    ?>
                    <div class="technical-card">
                        <div class="technical-icon"><?php echo $data['icon']; ?></div>
                        <div class="technical-content">
                            <h3 class="technical-label"><?php echo $data['label']; ?></h3>
                            <div class="technical-tags">
                                <?php foreach ($terms as $term) : 
                                    $term_link = get_term_link($term);
                                    if (!is_wp_error($term_link)) : ?>
                                    <a href="<?php echo esc_url($term_link); ?>" class="technical-tag">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                    <?php else : ?>
                                    <span class="technical-tag">
                                        <?php echo esc_html($term->name); ?>
                                    </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                    
                    <!-- Дополнительные поля -->
                    <?php foreach ($additional_fields as $field => $data) : 
                        $value = get_post_meta($work_id, $field, true);
                        if (!empty($value)) : 
                    ?>
                    <div class="technical-card">
                        <div class="technical-icon"><?php echo $data['icon']; ?></div>
                        <div class="technical-content">
                            <h3 class="technical-label"><?php echo $data['label']; ?></h3>
                            <?php if ($field === 'otziv') : ?>
                                <div class="client-review">
                                    <p class="technical-value"><?php echo wpautop(esc_html($value)); ?></p>
                                </div>
                            <?php else : ?>
                                <p class="technical-value"><?php echo esc_html($value); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                    
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- СВЯЗАННЫЕ статьи -->
        <?php
$related_labs = get_post_meta(get_the_ID(), 'related_lab_entry', false); // false = массив
if (!empty($related_labs)) :
?>
    <div class="work-lab-links">
        <p class="work-lab-links__heading">→ Читать в Лаборатории:</p>
        <ul class="work-lab-links__list">
            <?php foreach ($related_labs as $lab_id) : 
                $lab_post = get_post($lab_id);
                if ($lab_post) :
            ?>
                <li>
                    <a href="<?php echo get_permalink($lab_post); ?>">
                        «<?php echo esc_html($lab_post->post_title); ?>»
                    </a>
                </li>
            <?php 
                endif;
            endforeach; 
            ?>
        </ul>
    </div>
<?php endif; ?>
		
		<!-- СВЯЗАННЫЕ РАБОТЫ -->
        <?php /*
		// Пример с параметром (если нужно изменить количество работ)
		set_query_var('related_count', 6);
		get_template_part('template-parts/related-works'); 
		*/?>
		
		
		        <!-- ГРАФ СВЯЗЕЙ -->
        <?php get_template_part('template-parts/related-graph'); ?>

        <!-- Единая навигация между работами -->
        <nav class="work-navigation">
            <div class="nav-previous">
                <?php previous_post_link('%link', '← %title'); ?>
            </div>
            <div class="nav-all">
                <a href="<?php echo get_post_type_archive_link('work'); ?>">
                    📚 Все исследования
                </a>
            </div>
            <div class="nav-next">
                <?php next_post_link('%link', '%title →'); ?>
            </div>
        </nav>

        <!-- КОММЕНТАРИИ -->
        <?php if (comments_open() || get_comments_number()) : ?>
        <section class="work-section work-comments">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">💬</span>
                    Обсуждение
                </h2>
                <div class="section-subtitle">Вопросы и рефлексия</div>
            </div>
            <div class="section-content">
                <?php comments_template(); ?>
            </div>
        </section>
        <?php endif; ?>

    </div> <!-- .deboart-work-container -->
    
</main> <!-- .deboart-work-main -->

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>