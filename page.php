<?php
/**
 * Template Name: Page
 * Общий шаблон для всех страниц
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';

 ?>

<main id="primary" class="site-main deboart-page">
    <div class="deboart-page-container">
        
        <!-- Шапка страницы -->
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <header class="page-header">
                <div class="page-header-row">
                    <h1 class="page-title has-hero-font-size"><?php the_title(); ?></h1>
                </div>
                
                <?php if (has_excerpt()) : ?>
                    <div class="page-intro">
                        <p class="page-intro-text"><?php echo get_the_excerpt(); ?></p>
                    </div>
                <?php endif; ?>
            </header>

            <!-- Основной контент -->
            <article class="page-content">
                <?php the_content(); ?>
            </article>

        <?php endwhile; endif; ?>
        
    </div>
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>