<?php
/**
 * Template Name: Архив лаборатории
 * Description: Шаблон для рубрики Лаборатория
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';

 ?>

<main id="primary" class="site-main lab-archive">
    
    <!-- Весь контент теперь внутри lab-archive__content -->
    <div class="lab-archive__content">
    
        <!-- Заголовок -->
        <header class="lab-archive__header">
            <h1 class="lab-archive__title">ЛАБОРАТОРИЯ</h1>
            <p class="lab-archive__description">
                Живой дневник исследований, инсайтов и экспериментов
            </p>
        </header>

        <!-- Сетка статей -->
        <div class="lab-archive__grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article class="lab-entry-card">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="lab-entry-image">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="lab-entry-content">
                            
                            <div class="lab-entry-meta">
                                <span class="lab-entry-icons">
                                    <?php 
                                    $related_work = get_field('related_work');
                                    if ($related_work) {
                                        $form = get_the_terms($related_work->ID, 'form');
                                        if ($form) {
                                            echo get_term_meta($form[0]->term_id, 'emoji', true);
                                        }
                                    }
                                    ?>
                                </span>
                                
                                <span class="lab-entry-date">
                                    <?php echo get_the_date('d.m.Y'); ?>
                                </span>
                            </div>
                            
                            <h2 class="lab-entry-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <div class="lab-entry-description">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="lab-entry-button">
                                Читать
                            </a>
                        </div>
                    </article>
                    
                <?php endwhile; ?>
                
                <!-- Пагинация -->
                <div class="lab-archive__pagination">
                    <?php echo paginate_links(); ?>
                </div>
                
            <?php else : ?>
                <p class="lab-empty">Записей пока нет. Скоро появятся!</p>
            <?php endif; ?>
        </div>
        
    </div> <!-- /lab-archive__content -->
    
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>