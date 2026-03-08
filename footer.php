<?php
/**
 * Footer template
 * 
 * @package tt4-deboart
 */
?>

    <!-- Закрываем контентную часть -->
    </div><!-- .site-content -->
</div><!-- #page -->

<footer class="deboart-footer">
    <div class="footer-container">
        
        <!-- Основные колонки футера -->
        <div class="footer-widgets">
            
            <!-- Колонка 1: Логотип и описание -->
            <div class="footer-column">
                <div class="footer-branding">
                    
                    <!-- Логотип (картинка) -->
                    <div class="custom-logo">
                        <img src="<?php echo esc_url(get_theme_mod('footer_logo', '/wp-content/uploads/slavadbart-logo-6-1.png')); ?>" 
                             alt="Deboart" 
                             width="60" 
                             height="60">
                    </div>
                    
                    <!-- Текстовый логотип и описание -->
                    <div class="footer-branding-text">
                        <h2 class="footer-logo">
                            DEBO<span class="art-accent">ART</span>
                        </h2>
                        <p class="footer-tagline">
                            Лаборатория творческого потенциала.<br>
                            Вопрос к форме. Ответ — в содержании.
                        </p>
                    </div>
                    
                </div>
            </div>
            
            <!-- Колонка 2: Исследования (динамические посты) -->
            <div class="footer-column">
                <h3 class="footer-title">🔬 Исследования</h3>
                
                <?php
                $recent_works = new WP_Query(array(
                    'post_type'      => 'work',
                    'posts_per_page' => 4,
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                ));
                
                if ($recent_works->have_posts()) : ?>
                    <ul class="footer-works-list">
                        <?php while ($recent_works->have_posts()) : $recent_works->the_post(); ?>
                            <li class="footer-work-item">
                                <a href="<?php the_permalink(); ?>" class="footer-link">
                                    <?php the_title(); ?>
                                </a>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                <?php else : ?>
                    <p class="footer-empty">Исследования скоро появятся</p>
                <?php endif; ?>
                
            </div>
            
            <!-- Колонка 3: Контакты -->
            <div class="footer-column">
                <h3 class="footer-title">📡 Сеанс связи</h3>
                
                <div class="footer-contacts">
                    
                    <!-- Email -->
                    <p class="contact-item">
                        📧 <a href="mailto:seans@deboart.ru">seans@deboart.ru</a>
                    </p>
                    
                    <!-- Telegram -->
                    <p class="contact-item">
                        📱 <a href="https://t.me/deboart_lab" target="_blank" rel="noopener">Telegram: Тайная лаборатория</a>
                    </p>
                    
                    <!-- Социальные сети с иконками -->
<div class="social-links">
    <a href="https://instagram.com/deboart" class="social-link instagram" target="_blank" rel="noopener" aria-label="Instagram">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 11.37C16.1234 12.2022 15.9812 13.0522 15.5937 13.799C15.2062 14.5458 14.5931 15.1514 13.8416 15.5297C13.0901 15.9079 12.2384 16.0396 11.4077 15.9059C10.5771 15.7723 9.80971 15.3801 9.21479 14.7852C8.61987 14.1903 8.22768 13.4229 8.09402 12.5923C7.96035 11.7616 8.09202 10.9099 8.47028 10.1584C8.84854 9.40685 9.45414 8.79374 10.2009 8.40624C10.9477 8.01874 11.7977 7.87659 12.63 8C13.4789 8.12588 14.2648 8.52146 14.8716 9.1283C15.4785 9.73515 15.8741 10.5211 16 11.37Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M17.5 6.5H17.51" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span class="social-text">Instagram</span>
    </a>
    
    <a href="https://behance.net/deboart" class="social-link behance" target="_blank" rel="noopener" aria-label="Behance">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 11H12C13.1046 11 14 10.1046 14 9V8C14 6.89543 13.1046 6 12 6H8V11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 18H13C14.1046 18 15 17.1046 15 16V15C15 13.8954 14.1046 13 13 13H8V18Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M18 13H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 6H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 12H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 18H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span class="social-text">Behance</span>
    </a>
    
    <a href="https://youtube.com/@deboart" class="social-link youtube" target="_blank" rel="noopener" aria-label="YouTube">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M22 8.5C22 7.12 20.88 6 19.5 6H4.5C3.12 6 2 7.12 2 8.5V15.5C2 16.88 3.12 18 4.5 18H19.5C20.88 18 22 16.88 22 15.5V8.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 15L15 12L10 9V15Z" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span class="social-text">YouTube</span>
    </a>
</div>
                    
                </div>
            </div>
            
        </div><!-- .footer-widgets -->
        
        <!-- Разделитель -->
        <div class="footer-divider">
            <div class="divider-line"></div>
            <p class="divider-label">КОНЕЦ СЕАНСА</p>
        </div>
        
        <!-- Нижняя часть футера -->
        <div class="footer-bottom">
            
            <p class="copyright">
                © <?php echo date('Y'); ?> DEBOART. Все исследования защищены.<br>
                <span class="copyright-note">Творчество — это не талант. Это режим доступа.</span>
            </p>
            
            <div class="footer-links-bottom">
                <a href="/privacy">Конфиденциальность</a>
                <span class="link-separator">•</span>
                <a href="/manifest">Манифест</a>
                <span class="link-separator">•</span>
                <a href="/lab">Войти в лабораторию</a>
            </div>
            
        </div><!-- .footer-bottom -->
        
    </div><!-- .footer-container -->
</footer>

<?php wp_footer(); ?>

</body>
</html>