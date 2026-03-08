<?php
/**
 * Header template
 * 
 * @package tt4-deboart
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>


<body <?php body_class('deboart-custom-template'); ?>>
<?php wp_body_open(); ?>
<!-- header -->
<div id="page" class="site">
    <header class="deboart-header">
        <div class="header-container">
            
            <!-- Логотип и название -->
            <div class="site-branding">
                
                <!-- Кастомный логотип (картинка) -->
                <?php if (has_custom_logo()) : ?>
                    <div class="custom-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <div class="custom-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img src="<?php echo esc_url(get_theme_mod('header_logo', '/wp-content/uploads/slavadbart-logo-6-1.png')); ?>" 
                                 alt="<?php bloginfo('name'); ?>" 
                                 width="60" 
                                 height="60">
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Текстовый логотип -->
                <div class="site-title-wrapper">
                    <?php if (is_front_page()) : ?>
                        <h1 class="header-logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>">DEBO<span class="art-accent">ART</span></a>
                        </h1>
                    <?php else : ?>
                        <p class="header-logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>">DEBO<span class="art-accent">ART</span></a>
                        </p>
                    <?php endif; ?>
                    
                    <p class="site-description"><?php bloginfo('description'); ?></p>
                </div>
                
            </div>
            
         <!-- Навигация -->
<nav class="deboart-navigation">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_class'     => 'primary-menu',
        'container'      => false,
        'fallback_cb'    => 'deboart_primary_menu_fallback', // Функция, если меню не создано
        'depth'          => 2, // Глубина вложенности (для выпадающих меню)
        'walker'         => new Deboart_Walker_Nav_Menu(), // Кастомный Walker для доп. классов
        'link_before'    => '<span class="menu-item-text">',
        'link_after'     => '</span>',
    ));
    ?>
</nav>  
             
                        <!-- Кнопка CTA -->
            <div class="header-actions">
 <!-- Кнопка Случайная работа -->
<a href="<?php echo esc_url(get_random_work_url()); ?>" class="header-random" title="Случайная работа">
    <span class="random-icon">🎲</span>
    <span class="random-text">Случайная</span>
</a>

<!-- Кнопка Сеанс связи -->
<a href="<?php echo esc_url(get_theme_mod('cta_url', '/сеанс')); ?>" class="header-cta" title="<?php echo esc_attr(get_theme_mod('cta_text', 'Сеанс связи')); ?>">
    <span class="cta-icon">💬</span>
    <span class="cta-text"><?php echo esc_html(get_theme_mod('cta_text', 'Сеанс связи')); ?></span>
</a>
            </div>
            
        </div><!-- .header-container -->
        
        <!-- Декоративный разделитель -->
        <div class="header-divider">
            <div class="divider-line"></div>
            <div class="divider-dots">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
        
    </header>
    
    <div class="site-content">