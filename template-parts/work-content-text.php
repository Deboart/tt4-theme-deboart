<?php
/**
 * Контент для работы с формой "Текст"
 * 
 * @var int $work_id
 */

$work_id = get_the_ID();
?>

<!-- ОСНОВНОЙ ТЕКСТ (акцент) -->
<?php if (get_the_content()) : ?>
    <article class="work-content">
        <?php the_content(); ?>
    </article>
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