<?php
/**
 * Custom header for work archive
 * This file calls the template part from site editor
 */

// Получаем ID части шаблона
$template_part_id = get_stylesheet() . '//header-deboart';
$template_part = get_block_template($template_part_id, 'wp_template_part');

// Выводим HTML структуру
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class('deboart-archive-work'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site deboart-site">
    <?php if ($template_part && !empty($template_part->content)) : ?>
        <?php echo do_blocks($template_part->content); ?>
    <?php else : ?>
        <?php get_header(); // Fallback ?>
    <?php endif; ?>