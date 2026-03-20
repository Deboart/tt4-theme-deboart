<?php
/**
 * Template Name: Манифест
 */

// Подключаем header
require get_stylesheet_directory() . '/template-parts/site-header.php';

 ?>

<main class="manifest-page">
    
    <!-- Блок 1: Вопрос -->
    <section class="manifest-block manifest-question">
        <div class="manifest-container">
            <h1 class="manifest-question__heading">МЕНЯ ВСЕГДА МУЧИЛ ОДИН ВОПРОС:</h1>
            <div class="manifest-question__text">
                «Что, если я не знаю, кто я?<br>
                Что, если я не могу выбрать одну форму?<br>
                Что, если мне интересно всё — но ненадолго?»
            </div>
        </div>
    </section>
    
    <!-- Блок 2: Из хаоса в систему -->
    <section class="manifest-block manifest-story">
        <div class="manifest-container manifest-story__content">
            <!-- текст из Блока 2 -->
        </div>
    </section>
    
    <!-- Блок 3: Избранные доказательства -->
    <section class="manifest-block manifest-featured">
        <div class="manifest-container">
            <h2 class="manifest-featured__heading">ИЗБРАННЫЕ ДОКАЗАТЕЛЬСТВА</h2>
            <p class="manifest-featured__subheading">
                Каждый раз — три случайные работы из тех,<br>
                что стали вехами на этом пути.
            </p>
            
            <div class="manifest-featured__grid">
                <?php
                $featured_works = get_posts(array(
                    'post_type' => 'work',
                    'posts_per_page' => 3,
                    'meta_key' => 'featured',
                    'meta_value' => true,
                    'orderby' => 'rand'
                ));
                
                foreach ($featured_works as $work) :
                    // вывод карточки работы
                endforeach;
                ?>
            </div>
            
            <div class="manifest-featured__action">
                <a href="/works" class="manifest-button">Посмотреть все исследования →</a>
            </div>
        </div>
    </section>
    
    <!-- Блок 4: Ваш хаос тоже имеет систему -->
    <section class="manifest-block manifest-invitation">
        <div class="manifest-container manifest-invitation__content">
            <!-- текст из Блока 4 -->
        </div>
    </section>
    
    <!-- Блок 5: Подпись -->
    <section class="manifest-block manifest-signature">
        <div class="manifest-container">
            <div class="manifest-signature__text">
                Асс Дебо<br>
                Март 2026
            </div>
        </div>
    </section>
    
</main>

<?php
require get_stylesheet_directory() . '/template-parts/site-footer.php';
?>