<?php
/**
 * Секция "Форма → Содержание" - интерактивная схема
 */

// Получаем таксономии для динамического наполнения
$forms = get_terms(array(
    'taxonomy' => 'work_form',
    'hide_empty' => false,
    'orderby' => 'name'
));

$feelings = get_terms(array(
    'taxonomy' => 'work_feeling',
    'hide_empty' => false,
    'orderby' => 'name'
));

// Массивы иконок и описаний
$form_icons = array(
    'text' => '📖',
    'image' => '🖼️',
    'video' => '🎬',
    'audio' => '🎵',
    'web' => '🌐',
    'object' => '✨'
);

$feeling_icons = array(
    'tishina' => '😌',
    'energy' => '⚡',
    'thought' => '🤔',
    'drama' => '🎭',
    'chaos' => '🌀',
    'memory' => '🕰️'
);

$feeling_descriptions = array(
    'tishina' => 'Тишина и созерцание',
    'energy' => 'Энергия и движение',
    'thought' => 'Мысль и рефлексия',
    'drama' => 'Драма и напряжение',
    'chaos' => 'Хаос и случайность',
    'memory' => 'Память и время'
);
?>

<section class="front-section deboart-paradigm-section">
<div class="metal-ice"></div>
    <div class="metal-grain"></div>

    <div class="wp-block-group__inner-container">
        
        <h2 class="paradigm-heading">ФОРМА → СОДЕРЖАНИЕ</h2>
        
        <div class="paradigm-diagram-container">
            
            <div class="paradigm-diagram" id="deboartParadigmDiagram">
                
                <!-- Верхний блок: ФОРМА -->
                <div class="paradigm-block paradigm-form" data-type="form">
                    <div class="paradigm-icon">🎨</div>
                    <div class="paradigm-label">ФОРМА</div>
                    <div class="paradigm-description">Что это?</div>
                </div>
                
                <!-- Стрелка вниз -->
                <div class="paradigm-arrow">↓</div>
                
                <!-- Средний блок: формы -->
                <div class="paradigm-forms-grid">
                    <?php if (!empty($forms) && !is_wp_error($forms)) : ?>
                        <?php foreach ($forms as $form) : 
                            $icon = isset($form_icons[$form->slug]) ? $form_icons[$form->slug] : '🎨';
                            
                            // Получаем пример работы для этой формы
                            $example_query = new WP_Query(array(
                                'post_type' => 'work',
                                'tax_query' => array(array(
                                    'taxonomy' => 'work_form',
                                    'field' => 'slug',
                                    'terms' => $form->slug
                                )),
                                'posts_per_page' => 1,
                                'fields' => 'ids',
                                'no_found_rows' => true
                            ));
                            
                            $example_title = '';
                            if ($example_query->have_posts()) {
                                $example_query->the_post();
                                $example_title = get_the_title();
                                wp_reset_postdata();
                            }
                        ?>
                            <div class="paradigm-item" 
                                 data-form="<?php echo esc_attr($form->slug); ?>" 
                                 data-example="<?php echo esc_attr($example_title); ?>">
                                <span class="paradigm-item-icon"><?php echo $icon; ?></span>
                                <span class="paradigm-item-label"><?php echo esc_html($form->name); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Статичные данные на случай отсутствия таксономий -->
                        <div class="paradigm-item" data-form="text" data-example="Бесконечное зеркало">
                            <span class="paradigm-item-icon">📖</span>
                            <span class="paradigm-item-label">Текст</span>
                        </div>
                        <div class="paradigm-item" data-form="image" data-example="Логотип DEBOART">
                            <span class="paradigm-item-icon">🖼️</span>
                            <span class="paradigm-item-label">Изображение</span>
                        </div>
                        <div class="paradigm-item" data-form="video" data-example="Видеоклип">
                            <span class="paradigm-item-icon">🎬</span>
                            <span class="paradigm-item-label">Видео</span>
                        </div>
                        <div class="paradigm-item" data-form="audio" data-example="Звуки тишины">
                            <span class="paradigm-item-icon">🎵</span>
                            <span class="paradigm-item-label">Аудио</span>
                        </div>
                        <div class="paradigm-item" data-form="web" data-example="Интерактивная поэзия">
                            <span class="paradigm-item-icon">🌐</span>
                            <span class="paradigm-item-label">Веб</span>
                        </div>
                        <div class="paradigm-item" data-form="object" data-example="Хрупкая вечность">
                            <span class="paradigm-item-icon">✨</span>
                            <span class="paradigm-item-label">Объект</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Стрелка вниз -->
                <div class="paradigm-arrow">↓</div>
                
                <!-- Нижний блок: СОДЕРЖАНИЕ -->
                <div class="paradigm-block paradigm-content" data-type="content">
                    <div class="paradigm-icon">💭</div>
                    <div class="paradigm-label">СОДЕРЖАНИЕ</div>
                    <div class="paradigm-description">О чём/какое чувство?</div>
                </div>
                
                <!-- Стрелка вниз -->
                <div class="paradigm-arrow">↓</div>
                
                <!-- Нижний блок: содержания -->
                <div class="paradigm-content-grid">
                    <?php if (!empty($feelings) && !is_wp_error($feelings)) : ?>
                        <?php foreach ($feelings as $feeling) : 
                            $icon = isset($feeling_icons[$feeling->slug]) ? $feeling_icons[$feeling->slug] : '💭';
                            $description = isset($feeling_descriptions[$feeling->slug]) ? $feeling_descriptions[$feeling->slug] : '';
                        ?>
                            <div class="paradigm-item" 
                                 data-content="<?php echo esc_attr($feeling->slug); ?>" 
                                 data-description="<?php echo esc_attr($description); ?>">
                                <span class="paradigm-item-icon"><?php echo $icon; ?></span>
                                <span class="paradigm-item-label"><?php echo esc_html($feeling->name); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Статичные данные на случай отсутствия таксономий -->
                        <div class="paradigm-item" data-content="silence" data-description="Тишина и созерцание">
                            <span class="paradigm-item-icon">😌</span>
                            <span class="paradigm-item-label">Тишина</span>
                        </div>
                        <div class="paradigm-item" data-content="energy" data-description="Энергия и движение">
                            <span class="paradigm-item-icon">⚡</span>
                            <span class="paradigm-item-label">Энергия</span>
                        </div>
                        <div class="paradigm-item" data-content="thought" data-description="Мысль и рефлексия">
                            <span class="paradigm-item-icon">🤔</span>
                            <span class="paradigm-item-label">Мысль</span>
                        </div>
                        <div class="paradigm-item" data-content="drama" data-description="Драма и напряжение">
                            <span class="paradigm-item-icon">🎭</span>
                            <span class="paradigm-item-label">Драма</span>
                        </div>
                        <div class="paradigm-item" data-content="chaos" data-description="Хаос и случайность">
                            <span class="paradigm-item-icon">🌀</span>
                            <span class="paradigm-item-label">Хаос</span>
                        </div>
                        <div class="paradigm-item" data-content="memory" data-description="Память и время">
                            <span class="paradigm-item-icon">🕰️</span>
                            <span class="paradigm-item-label">Память</span>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
            
            <!-- Всплывающая подсказка (tooltip) - будет заполняться через JS -->
            <div class="paradigm-tooltip" id="paradigmTooltip">
                <div class="tooltip-content">
                    <h4 class="tooltip-title">Пример работы</h4>
                    <p class="tooltip-description"></p>
                    <div class="tooltip-examples"></div>
                </div>
            </div>
            
        </div>
        
        <!-- Поясняющий текст -->
        <p class="paradigm-description">
            Наведите на любой элемент, чтобы увидеть примеры работ<br>
            <small>Каждая работа — это пересечение формы и содержания</small>
        </p>
        
    </div>
</section>

<?php
// Подключаем JavaScript для интерактивности
// В идеале - вынести в отдельный файл и подключать через wp_enqueue_script
?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/paradigm-diagram.js"></script>