<?php
/**
 * Секция "Контакты" - форма связи
 * 
 * Готова к интеграции с Contact Form 7
 * ID формы: 5987
 */

$contact_heading = get_theme_mod('contact_heading', 'ВАШ СЛЕДУЮЩИЙ ВОПРОС — КАКОЙ?');
$contact_subtitle = get_theme_mod('contact_subtitle', 'Каждое исследование начинается с вопроса. Расскажите о вашей идее, проекте или просто поделитесь мыслью — это может стать началом следующего эксперимента.');
?>

<section class="front-section deboart-contact-section">
    <div class="wp-block-group__inner-container">
        
        <h2 class="wp-block-heading has-text-align-center contact-heading"><?php echo esc_html($contact_heading); ?></h2>
        
        <p class="has-text-align-center contact-subtitle"><?php echo esc_html($contact_subtitle); ?></p>
        
        <div class="wp-block-group contact-form-container">
            
            <?php if (shortcode_exists('contact-form-7')) : ?>
                <!-- Реальная форма Contact Form 7 -->
                <?php echo do_shortcode('[contact-form-7 id="5987" title="Контактная форма"]'); ?>
            <?php else : ?>
                <!-- Сообщение об ошибке, если CF7 не установлен -->
                <p class="has-text-align-center contact-form-error">
                    ⚠️ Плагин Contact Form 7 не установлен или не активирован. Пожалуйста, активируйте плагин для работоспособности формы.
                </p>
            <?php endif; ?>
            
        </div>
        
        <!-- Нижняя навигация -->
        <div class="wp-block-group contact-bottom-navigation">
            
            <div class="contact-nav-links">
                <a href="<?php echo home_url('/'); ?>" class="contact-nav-link <?php echo is_front_page() ? 'active' : ''; ?>">DEBO</a>
                <a href="<?php echo get_post_type_archive_link('work'); ?>" class="contact-nav-link">ИССЛЕДОВАНИЯ</a>
                <a href="<?php echo home_url('/category/lab/'); ?>" class="contact-nav-link">ЛАБОРАТОРИЯ</a>
                <a href="<?php echo home_url('/protocols/'); ?>" class="contact-nav-link">ПРОТОКОЛЫ</a>
                <a href="<?php echo home_url('/contact/'); ?>" class="contact-nav-link">СЕАНС</a>
            </div>
            
            <p class="has-text-align-center contact-copyright">
                Все исследования защищены концепцией.<br>
                Этот сайт — часть продолжающегося эксперимента.
            </p>
            
        </div>
        
    </div>
</section>