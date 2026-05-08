<?php
/**
 * Технические детали проекта (таксономии и дополнительные поля)
 */

$work_id = get_the_ID();

// Таксономии для отображения
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

// Дополнительные поля
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

if ($has_taxonomies || $has_fields) : ?>
<section class="work-section work-technical">
    <div class="section-header">
        <h2 class="section-title"><span class="section-icon">⚙️</span> Детали проекта</h2>
        <div class="section-subtitle">Метаданные и классификация</div>
    </div>
    <div class="section-content">
        <div class="technical-grid">
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
                                    <a href="<?php echo esc_url($term_link); ?>" class="technical-tag"><?php echo esc_html($term->name); ?></a>
                                <?php else : ?>
                                    <span class="technical-tag"><?php echo esc_html($term->name); ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; endforeach; ?>
            
            <?php foreach ($additional_fields as $field => $data) : 
                $value = get_post_meta($work_id, $field, true);
                if (!empty($value)) : 
            ?>
                <div class="technical-card">
                    <div class="technical-icon"><?php echo $data['icon']; ?></div>
                    <div class="technical-content">
                        <h3 class="technical-label"><?php echo $data['label']; ?></h3>
                        <?php if ($field === 'otziv') : ?>
                            <div class="client-review"><p class="technical-value"><?php echo wpautop(esc_html($value)); ?></p></div>
                        <?php else : ?>
                            <p class="technical-value"><?php echo esc_html($value); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>