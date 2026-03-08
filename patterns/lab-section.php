<?php
/**
 * Title: Deboart Lab Section
 * Slug: deboart/lab-section
 * Categories: featured
 * Description: Секция "Лаборатория" - живой дневник исследований
 */
?>
<!-- wp:group {"align":"full","className":"deboart-lab-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|huge","bottom":"var:preset|spacing|huge"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull deboart-lab-section has-primary-black-background-color has-background" style="padding-top:var(--wp--preset--spacing--huge);padding-bottom:var(--wp--preset--spacing--huge);">

    <div class="wp-block-group__inner-container">

        <!-- Заголовок секции - ОБНОВЛЕН -->
        <!-- wp:heading {"textAlign":"center","className":"lab-heading","fontFamily":"spectral","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|large"}}}} -->
        <h2 class="wp-block-heading has-text-align-center lab-heading has-spectral-font-family" style="margin-bottom:var(--wp--preset--spacing--large);">
            ЛАБОРАТОРИЯ
        </h2>
        <!-- /wp:heading -->

        <!-- Подзаголовок - ОБНОВЛЕН -->
        <!-- wp:paragraph {"align":"center","className":"lab-subtitle","fontFamily":"manrope","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|x-large"}}}} -->
        <p class="has-text-align-center lab-subtitle has-manrope-font-family" style="margin-bottom:var(--wp--preset--spacing--x-large);">
            Живой дневник процесса. Эскизы, заметки, неудачные эксперименты и неожиданные озарения.<br>
            Здесь рождаются исследования.
        </p>
        <!-- /wp:paragraph -->

        <!-- Контейнер записей -->
        <!-- wp:group {"className":"lab-entries-container","layout":{"type":"constrained"}} -->
        <div class="wp-block-group lab-entries-container">

            <!-- ЗАПИСЬ 1 -->
            <!-- wp:group {"className":"lab-entry-card"} -->
            <div class="wp-block-group lab-entry-card">

                <!-- Заголовок записи с иконками - ОБНОВЛЕН -->
                <!-- wp:heading {"level":3,"className":"lab-entry-title","fontFamily":"spectral"} -->
                <h3 class="lab-entry-title has-spectral-font-family">
                    <span class="lab-entry-icons has-jetbrains-mono-font-family">
                        <span>📖</span>
                        <span>🎬</span>
                    </span>
                    <span>Как я превратил рутину в поэзию</span>
                </h3>
                <!-- /wp:heading -->

                <!-- Описание записи - ОБНОВЛЕН -->
                <!-- wp:paragraph {"className":"lab-entry-description","fontFamily":"manrope"} -->
                <p class="lab-entry-description has-manrope-font-family">
                    Эксперимент по документированию обычного дня через три разных медиа: текст, фото и короткое видео.
                    Что если ежедневная рутина — это уже готовый перформанс? Главный инсайт: поэзия не в событиях, а в способе их фиксации.
                </p>
                <!-- /wp:paragraph -->

                <!-- Мета-информация -->
                <div class="lab-entry-meta">
                    <!-- Дата - ОБНОВЛЕН -->
                    <!-- wp:paragraph {"className":"lab-entry-date","fontFamily":"jetbrains-mono"} -->
                    <p class="lab-entry-date has-jetbrains-mono-font-family">2026-01-14</p>
                    <!-- /wp:paragraph -->

                    <!-- Кнопка "Читать" - ОБНОВЛЕН -->
                    <!-- wp:button {"className":"lab-entry-button","backgroundColor":"accent-blue","textColor":"primary-white","style":{"border":{"width":"1px"}},"fontFamily":"manrope"} -->
                    <div class="wp-block-button lab-entry-button">
                        <a class="wp-block-button__link has-accent-blue-background-color has-primary-white-color has-background has-link-color wp-element-button has-manrope-font-family" href="/lab/как-я-превратил-рутину-в-поэзию/" style="border-width:1px">
                            Читать
                        </a>
                    </div>
                    <!-- /wp:button -->
                </div>

            </div>
            <!-- /wp:group -->

            <!-- ЗАПИСЬ 2 -->
            <!-- wp:group {"className":"lab-entry-card"} -->
            <div class="wp-block-group lab-entry-card">

                <!-- Заголовок записи с иконками - ОБНОВЛЕН -->
                <!-- wp:heading {"level":3,"className":"lab-entry-title","fontFamily":"spectral"} -->
                <h3 class="lab-entry-title has-spectral-font-family">
                    <span class="lab-entry-icons has-jetbrains-mono-font-family">
                        <span>🖼️</span>
                        <span>🤔</span>
                    </span>
                    <span>Почему пустое пространство говорит громче заполненного</span>
                </h3>
                <!-- /wp:heading -->

                <!-- Описание записи - ОБНОВЛЕН -->
                <!-- wp:paragraph {"className":"lab-entry-description","fontFamily":"manrope"} -->
                <p class="lab-entry-description has-manrope-font-family">
                    Серия из 5 минималистичных композиций, где главным героем стало отсутствие.
                    Исследование негативного пространства как активного элемента. Неожиданный вывод: иногда убрать — значит добавить.
                </p>
                <!-- /wp:paragraph -->

                <!-- Мета-информация -->
                <div class="lab-entry-meta">
                    <!-- Дата - ОБНОВЛЕН -->
                    <!-- wp:paragraph {"className":"lab-entry-date","fontFamily":"jetbrains-mono"} -->
                    <p class="lab-entry-date has-jetbrains-mono-font-family">2026-01-08</p>
                    <!-- /wp:paragraph -->

                    <!-- Кнопка "Читать" - ОБНОВЛЕН -->
                    <!-- wp:button {"className":"lab-entry-button","backgroundColor":"accent-blue","textColor":"primary-white","style":{"border":{"width":"1px"}},"fontFamily":"manrope"} -->
                    <div class="wp-block-button lab-entry-button">
                        <a class="wp-block-button__link has-accent-blue-background-color has-primary-white-color has-background has-link-color wp-element-button has-manrope-font-family" href="/lab/пустое-пространство-говорит-громче/" style="border-width:1px">
                            Читать
                        </a>
                    </div>
                    <!-- /wp:button -->
                </div>
                
            </div>
            <!-- /wp:group -->

        </div>
        <!-- /wp:group -->

        <!-- Ссылка на все записи - ОБНОВЛЕН -->
        <!-- wp:paragraph {"align":"center","className":"lab-all-entries"} -->
        <p class="has-text-align-center lab-all-entries">
            <!-- wp:button {"className":"lab-all-entries-link","backgroundColor":"primary-black","textColor":"primary-white","fontFamily":"manrope"} -->
            <div class="wp-block-button lab-all-entries-link">
                <a class="wp-block-button__link has-primary-black-background-color has-primary-white-color has-background has-link-color wp-element-button has-manrope-font-family" href="/category/lab/">
                    Все записи лаборатории
                </a>
            </div>
            <!-- /wp:button -->
        </p>
        <!-- /wp:paragraph -->

    </div>

</div>
<!-- /wp:group -->