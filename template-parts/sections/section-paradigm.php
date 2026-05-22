<?php
/**
 * Секция "Форма → Содержание" - интерактивная схема
 * С динамическими примерами работ и кэшированием
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
    'text'   => '📖',
    'image'  => '🖼️',
    'video'  => '🎬',
    'audio'  => '🎵',
    'web'    => '🌐',
    'object' => '✨'
);

$feeling_icons = array(
    'tishina' => '😌',
    'energy'  => '⚡',
    'thought' => '🤔',
    'drama'   => '🎭',
    'chaos'   => '🌀',
    'memory'  => '🕰️'
);

$feeling_descriptions = array(
    'tishina' => 'Тишина и созерцание',
    'energy'  => 'Энергия и движение',
    'thought' => 'Мысль и рефлексия',
    'drama'   => 'Драма и напряжение',
    'chaos'   => 'Хаос и случайность',
    'memory'  => 'Память и время'
);

// ============================================================
// ПОЛУЧАЕМ ПРИМЕРЫ РАБОТ С КЭШИРОВАНИЕМ
// ============================================================
function deboart_get_paradigm_examples() {
    // Пробуем получить данные из кэша
    $cached = get_transient('deboart_paradigm_examples');
    if (false !== $cached) {
        return $cached;
    }
    
    $form_examples = array();
    $feeling_examples = array();
    
    // Инициализируем пустыми массивами для всех форм
    $form_slugs = array();
    foreach (get_terms(array('taxonomy' => 'work_form', 'hide_empty' => false)) as $form) {
        $form_slugs[] = $form->slug;
        $form_examples[$form->slug] = array();
    }
    
    // Инициализируем для всех содержаний
    $feeling_slugs = array();
    foreach (get_terms(array('taxonomy' => 'work_feeling', 'hide_empty' => false)) as $feeling) {
        $feeling_slugs[] = $feeling->slug;
        $feeling_examples[$feeling->slug] = array();
    }
    
    // Запрос для форм: получаем работы, группируем по таксономии
    $form_query = new WP_Query(array(
        'post_type'      => 'work',
        'posts_per_page' => 30,
        'no_found_rows'  => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => true
    ));
    
    if ($form_query->have_posts()) {
        while ($form_query->have_posts()) {
            $form_query->the_post();
            $post_id = get_the_ID();
            $post_forms = wp_get_post_terms($post_id, 'work_form', array('fields' => 'slugs'));
            
            foreach ($post_forms as $form_slug) {
                if (in_array($form_slug, $form_slugs) && count($form_examples[$form_slug]) < 2) {
                    $form_examples[$form_slug][] = array(
                        'title' => get_the_title(),
                        'url'   => get_permalink()
                    );
                }
            }
        }
        wp_reset_postdata();
    }
    
    // Запрос для содержаний
    $feeling_query = new WP_Query(array(
        'post_type'      => 'work',
        'posts_per_page' => 30,
        'no_found_rows'  => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => true
    ));
    
    if ($feeling_query->have_posts()) {
        while ($feeling_query->have_posts()) {
            $feeling_query->the_post();
            $post_id = get_the_ID();
            $post_feelings = wp_get_post_terms($post_id, 'work_feeling', array('fields' => 'slugs'));
            
            foreach ($post_feelings as $feeling_slug) {
                if (in_array($feeling_slug, $feeling_slugs) && count($feeling_examples[$feeling_slug]) < 2) {
                    $feeling_examples[$feeling_slug][] = array(
                        'title' => get_the_title(),
                        'url'   => get_permalink()
                    );
                }
            }
        }
        wp_reset_postdata();
    }
    
    $result = array(
        'forms'    => $form_examples,
        'feelings' => $feeling_examples
    );
    
    // Сохраняем в кэш на 24 часа
    set_transient('deboart_paradigm_examples', $result, DAY_IN_SECONDS);
    
    return $result;
}

// Очищаем кэш при сохранении работы
add_action('save_post_work', function() {
    delete_transient('deboart_paradigm_examples');
});

// Получаем примеры
$examples = deboart_get_paradigm_examples();
$form_examples = $examples['forms'];
$feeling_examples = $examples['feelings'];
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
                            $examples_list = isset($form_examples[$form->slug]) ? $form_examples[$form->slug] : array();
                        ?>
                            <div class="paradigm-item" 
                                 data-form="<?php echo esc_attr($form->slug); ?>"
                                 data-examples='<?php echo json_encode($examples_list); ?>'>
                                <span class="paradigm-item-icon"><?php echo $icon; ?></span>
                                <span class="paradigm-item-label"><?php echo esc_html($form->name); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Статичные данные на случай отсутствия таксономий -->
                        <div class="paradigm-item" data-form="text" data-examples='[{"title":"Бесконечное зеркало","url":"\/works\/infinite-mirror"}]'>
                            <span class="paradigm-item-icon">📖</span>
                            <span class="paradigm-item-label">Текст</span>
                        </div>
                        <div class="paradigm-item" data-form="image" data-examples='[{"title":"Логотип DEBOART","url":"\/works\/deboart-logo"}]'>
                            <span class="paradigm-item-icon">🖼️</span>
                            <span class="paradigm-item-label">Изображение</span>
                        </div>
                        <div class="paradigm-item" data-form="video" data-examples='[{"title":"Видеоклип","url":"\/works\/video-clip"}]'>
                            <span class="paradigm-item-icon">🎬</span>
                            <span class="paradigm-item-label">Видео</span>
                        </div>
                        <div class="paradigm-item" data-form="audio" data-examples='[{"title":"Звуки тишины","url":"\/works\/sounds-of-silence"}]'>
                            <span class="paradigm-item-icon">🎵</span>
                            <span class="paradigm-item-label">Аудио</span>
                        </div>
                        <div class="paradigm-item" data-form="web" data-examples='[{"title":"Интерактивная поэзия","url":"\/works\/interactive-poetry"}]'>
                            <span class="paradigm-item-icon">🌐</span>
                            <span class="paradigm-item-label">Веб</span>
                        </div>
                        <div class="paradigm-item" data-form="object" data-examples='[{"title":"Хрупкая вечность","url":"\/works\/fragile-eternity"}]'>
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
                            $examples_list = isset($feeling_examples[$feeling->slug]) ? $feeling_examples[$feeling->slug] : array();
                        ?>
                            <div class="paradigm-item" 
                                 data-content="<?php echo esc_attr($feeling->slug); ?>"
                                 data-description="<?php echo esc_attr($description); ?>"
                                 data-examples='<?php echo json_encode($examples_list); ?>'>
                                <span class="paradigm-item-icon"><?php echo $icon; ?></span>
                                <span class="paradigm-item-label"><?php echo esc_html($feeling->name); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Статичные данные -->
                        <div class="paradigm-item" data-content="silence" data-description="Тишина и созерцание" data-examples='[{"title":"Медитация","url":"\/works\/meditation"}]'>
                            <span class="paradigm-item-icon">😌</span>
                            <span class="paradigm-item-label">Тишина</span>
                        </div>
                        <div class="paradigm-item" data-content="energy" data-description="Энергия и движение" data-examples='[{"title":"Танец","url":"\/works\/dance"}]'>
                            <span class="paradigm-item-icon">⚡</span>
                            <span class="paradigm-item-label">Энергия</span>
                        </div>
                        <div class="paradigm-item" data-content="thought" data-description="Мысль и рефлексия" data-examples='[{"title":"Размышление","url":"\/works\/reflection"}]'>
                            <span class="paradigm-item-icon">🤔</span>
                            <span class="paradigm-item-label">Мысль</span>
                        </div>
                        <div class="paradigm-item" data-content="drama" data-description="Драма и напряжение" data-examples='[{"title":"Конфликт","url":"\/works\/conflict"}]'>
                            <span class="paradigm-item-icon">🎭</span>
                            <span class="paradigm-item-label">Драма</span>
                        </div>
                        <div class="paradigm-item" data-content="chaos" data-description="Хаос и случайность" data-examples='[{"title":"Случай","url":"\/works\/random"}]'>
                            <span class="paradigm-item-icon">🌀</span>
                            <span class="paradigm-item-label">Хаос</span>
                        </div>
                        <div class="paradigm-item" data-content="memory" data-description="Память и время" data-examples='[{"title":"Воспоминание","url":"\/works\/memory"}]'>
                            <span class="paradigm-item-icon">🕰️</span>
                            <span class="paradigm-item-label">Память</span>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
            
            <!-- Всплывающая подсказка (tooltip) -->
<div class="paradigm-tooltip" id="paradigmTooltip">
    <div class="tooltip-content">
        <h4 class="tooltip-title">Примеры работ</h4>
        <p class="tooltip-description"></p> <!-- Этот элемент нужен -->
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

<script>
// Передаём данные в глобальную переменную для JS
window.deboartParadigmData = {
    forms: <?php echo json_encode($form_examples); ?>,
    feelings: <?php echo json_encode($feeling_examples); ?>
};
</script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/paradigm-diagram.js"></script>