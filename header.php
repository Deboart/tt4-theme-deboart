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
            
            <!-- Навигация для десктопа -->
            <nav class="deboart-navigation desktop-nav">
                <ul class="primary-menu">
                    <li class="menu-item"><a href="<?php echo esc_url(get_post_type_archive_link('work')); ?>"><span class="menu-item-text">ИССЛЕДОВАНИЯ</span></a></li>
                    <li class="menu-item"><a href="<?php echo esc_url(home_url('/lab/')); ?>"><span class="menu-item-text">ЛАБОРАТОРИЯ</span></a></li>
                    <li class="menu-item"><a href="<?php echo esc_url(home_url('/in-progress/')); ?>"><span class="menu-item-text">В РАБОТЕ</span></a></li>
                    <li class="menu-item"><a href="<?php echo esc_url(home_url('/manifest/')); ?>"><span class="menu-item-text">МАНИФЕСТ</span></a></li>
                    <li class="menu-item"><a href="<?php echo esc_url(home_url('/protocols/')); ?>"><span class="menu-item-text">ПРОТОКОЛЫ</span></a></li>
                </ul>
            </nav>
            
            <!-- Гамбургер-иконка для мобильных -->
            <button class="mobile-menu-toggle" aria-label="Меню">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <!-- Кнопки CTA (десктоп) -->
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
    
    <!-- Мобильное меню (скрыто по умолчанию) -->
    <div class="mobile-menu-overlay">
        <div class="mobile-menu-container">
            
            <!-- Шапка мобильного меню с логотипом -->
            <div class="mobile-menu-header">
                <div class="mobile-logo">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(get_theme_mod('header_logo', '/wp-content/uploads/slavadbart-logo-6-1.png')); ?>" 
                             alt="<?php bloginfo('name'); ?>" 
                             width="40" 
                             height="40">
                    <?php endif; ?>
                    <div class="mobile-site-title">
                        <span class="mobile-title">DEBO<span class="art-accent">ART</span></span>
                        <span class="mobile-description"><?php bloginfo('description'); ?></span>
                    </div>
                </div>
                <button class="mobile-menu-close">✕</button>
            </div>
            
            <!-- РУЧНАЯ НАВИГАЦИЯ -->
            <nav class="mobile-nav">
                <ul class="mobile-primary-menu">
                
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('work')); ?>">ИССЛЕДОВАНИЯ</a></li>
                    <li><a href="<?php echo esc_url(home_url('/lab/')); ?>">ЛАБОРАТОРИЯ</a></li>
                    <li><a href="<?php echo esc_url(home_url('/manifest/')); ?>">МАНИФЕСТ</a></li>
                    <li><a href="<?php echo esc_url(home_url('/protocols/')); ?>">ПРОТОКОЛЫ</a></li>
                </ul>
            </nav>
            
            <!-- Дополнительные ссылки -->
            <div class="mobile-extra-links">
                <h4 class="mobile-extra-title">Дополнительно</h4>
                <ul class="mobile-extra-menu">
                    <li><a href="/about">О проекте</a></li>
                    <li><a href="/contacts">Контакты</a></li>
                </ul>
            </div>
            
            <!-- Кнопки действий -->
            <div class="mobile-actions">
                <a href="<?php echo esc_url(get_random_work_url()); ?>" class="mobile-action-btn random-btn">
                    <span class="btn-icon">🎲</span>
                    <span class="btn-text">Случайная работа</span>
                </a>
                <a href="<?php echo esc_url(get_theme_mod('cta_url', '/сеанс')); ?>" class="mobile-action-btn cta-btn">
                    <span class="btn-icon">💬</span>
                    <span class="btn-text"><?php echo esc_html(get_theme_mod('cta_text', 'Сеанс связи')); ?></span>
                </a>
            </div>
            
        </div>
    </div>
    
    <div class="site-content">