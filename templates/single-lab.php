<?php
/**
 * Template Name: Запись лаборатории
 */

get_header();
?>

<main id="main" class="site-main lab-single">
    <?php while ( have_posts() ) : the_post(); ?>
        <article class="lab-entry">
            <div class="container">
                <header class="lab-entry__header">
                    <div class="lab-entry__meta">
                        <span class="lab-entry__date"><?php echo get_the_date(); ?></span>
                        <?php 
                        $reading_time = get_post_meta( get_the_ID(), 'reading_time', true );
                        if ( $reading_time ) : ?>
                            <span class="lab-entry__reading-time">· <?php echo $reading_time; ?> мин чтения</span>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="lab-entry__title"><?php the_title(); ?></h1>
                    
                    <?php if ( has_excerpt() ) : ?>
                        <div class="lab-entry__lead">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="lab-entry__content">
                    <?php the_content(); ?>
                </div>

                <?php 
                $related_works = get_post_meta( get_the_ID(), 'related_works', true );
                if ( $related_works ) : ?>
                    <section class="lab-entry__related-works">
                        <h2>Связанные работы</h2>
                        <div class="works-mini-grid">
                            <?php foreach ( $related_works as $work_id ) : ?>
                                <div class="work-mini-card">
                                    <?php echo get_the_post_thumbnail( $work_id, 'thumbnail' ); ?>
                                    <h3>
                                        <a href="<?php echo get_permalink( $work_id ); ?>">
                                            <?php echo get_the_title( $work_id ); ?>
                                        </a>
                                    </h3>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <footer class="lab-entry__footer">
                    <?php 
                    $tags = wp_get_post_terms( get_the_ID(), 'lab_tag' );
                    if ( $tags ) : ?>
                        <div class="lab-entry__tags">
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo get_term_link( $tag ); ?>" class="tag">
                                    #<?php echo $tag->name; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </footer>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();