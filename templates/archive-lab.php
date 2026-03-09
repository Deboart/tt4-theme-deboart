<?php
/**
 * Template Name: Архив лаборатории
 * Description: Отображает список записей лаборатории
 */

get_header();
?>

<main id="main" class="site-main lab-archive">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">🧪 Лаборатория</h1>
            <div class="page-description">
                Дневник исследований, мыслей и экспериментов
            </div>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="lab-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="lab-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="lab-card__image">
                                <?php the_post_thumbnail( 'medium' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="lab-card__content">
                            <h2 class="lab-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="lab-card__meta">
                                <span class="lab-card__date"><?php echo get_the_date(); ?></span>
                                <?php 
                                $reading_time = get_post_meta( get_the_ID(), 'reading_time', true );
                                if ( $reading_time ) : ?>
                                    <span class="lab-card__reading-time">· <?php echo $reading_time; ?> мин</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="lab-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <?php 
                            $related_works = get_post_meta( get_the_ID(), 'related_works', true );
                            if ( $related_works ) : ?>
                                <div class="lab-card__works">
                                    <span>Связанные работы:</span>
                                    <?php foreach ( $related_works as $work_id ) : ?>
                                        <a href="<?php echo get_permalink( $work_id ); ?>">
                                            <?php echo get_the_title( $work_id ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php the_posts_pagination(); ?>
            </div>

        <?php else : ?>
            <p>Записей пока нет. Скоро появятся!</p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();