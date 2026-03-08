<?php
// template-parts/works-grid.php
// Этот файл будет переиспользоваться и при AJAX и при обычной загрузке

global $wp_query;
$total_works = $wp_query->found_posts;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = get_option('posts_per_page');
$start = (($paged - 1) * $posts_per_page) + 1;
$end = min($paged * $posts_per_page, $total_works);
?>
<!-- Шаблон сетки работ -->
<div class="works-grid-header">
    <div class="works-stats">
        <?php if ($total_works > 0) : ?>
            <span class="stats-count">🕰️ Показано <?php echo $start; ?>—<?php echo $end; ?> из <?php echo $total_works; ?> работ</span>
        <?php else : ?>
            <span class="stats-count">Работы не найдены</span>
        <?php endif; ?>
    </div>
</div>

<?php if (have_posts()) : ?>
    <div class="works-grid">
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('parts/content', 'work-card'); ?>
        <?php endwhile; ?>
    </div>

    <!-- Пагинация -->
    <div class="works-pagination">
        <?php
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('← Предыдущая', 'tt4-deboart'),
            'next_text' => __('Следующая →', 'tt4-deboart'),
        ));
        ?>
    </div>
<?php else : ?>
    <div class="no-works-found">
        <div class="no-results-icon">🔍</div>
        <h3>Работы не найдены</h3>
        <p>Попробуйте изменить параметры фильтрации</p>
        <button type="button" class="no-results-reset" id="reset-from-empty">
            Сбросить фильтры
        </button>
    </div>
<?php endif; ?>