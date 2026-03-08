<?php
/**
 * Title: Deboart Paradigm Diagram
 * Slug: deboart/paradigm-diagram
 * Categories: featured
 * Description: Интерактивная схема парадигмы "Форма → Содержание"
 */
?>
<!-- wp:group {"align":"full","className":"deboart-paradigm-section","style":{"color":{"background":"var:preset|color|primary-white"},"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull deboart-paradigm-section has-primary-white-background-color has-background" style="padding-top:var(--wp--preset--spacing--x-large);padding-bottom:var(--wp--preset--spacing--x-large);">

    <div class="wp-block-group__inner-container">

        <!-- Заголовок секции - ОБНОВЛЕН -->
        <!-- wp:heading {"textAlign":"center","className":"paradigm-heading","fontFamily":"spectral"} -->
        <h2 class="wp-block-heading has-text-align-center paradigm-heading has-spectral-font-family">
            ФОРМА → СОДЕРЖАНИЕ
        </h2>
        <!-- /wp:heading -->

        <!-- Контейнер для интерактивной схемы -->
        <!-- wp:group {"className":"paradigm-diagram-container","layout":{"type":"constrained","contentSize":"800px"}} -->
        <div class="wp-block-group paradigm-diagram-container">

            <!-- Схема будет здесь -->
            <div class="paradigm-diagram" id="deboartParadigmDiagram">

                <!-- Верхний блок: ФОРМА -->
                <div class="paradigm-block paradigm-form" data-type="form">
                    <div class="paradigm-icon">🎨</div>
                    <div class="paradigm-label has-spectral-font-family">ФОРМА</div>
                    <div class="paradigm-description has-manrope-font-family">Что это?</div>
                </div>

                <!-- Стрелка вниз -->
                <div class="paradigm-arrow">↓</div>

                <!-- Средний блок: 6 форм -->
                <div class="paradigm-forms-grid">
                    <div class="paradigm-item" data-form="text" data-example="Бесконечное зеркало">
                        <span class="paradigm-item-icon">📖</span>
                        <span class="paradigm-item-label has-manrope-font-family">Текст</span>
                    </div>
                    <div class="paradigm-item" data-form="image" data-example="Логотип DEBOART">
                        <span class="paradigm-item-icon">🖼️</span>
                        <span class="paradigm-item-label has-manrope-font-family">Изображение</span>
                    </div>
                    <div class="paradigm-item" data-form="video" data-example="Видеоклип">
                        <span class="paradigm-item-icon">🎬</span>
                        <span class="paradigm-item-label has-manrope-font-family">Видео</span>
                    </div>
                    <div class="paradigm-item" data-form="audio" data-example="Звуки тишины">
                        <span class="paradigm-item-icon">🎵</span>
                        <span class="paradigm-item-label has-manrope-font-family">Аудио</span>
                    </div>
                    <div class="paradigm-item" data-form="web" data-example="Интерактивная поэзия">
                        <span class="paradigm-item-icon">🌐</span>
                        <span class="paradigm-item-label has-manrope-font-family">Веб</span>
                    </div>
                    <div class="paradigm-item" data-form="object" data-example="Хрупкая вечность">
                        <span class="paradigm-item-icon">✨</span>
                        <span class="paradigm-item-label has-manrope-font-family">Объект</span>
                    </div>
                </div>

                <!-- Стрелка вниз -->
                <div class="paradigm-arrow">↓</div>

                <!-- Нижний блок: СОДЕРЖАНИЕ -->
                <div class="paradigm-block paradigm-content" data-type="content">
                    <div class="paradigm-icon">💭</div>
                    <div class="paradigm-label has-spectral-font-family">СОДЕРЖАНИЕ</div>
                    <div class="paradigm-description has-manrope-font-family">О чём/какое чувство?</div>
                </div>

                <!-- Стрелка вниз -->
                <div class="paradigm-arrow">↓</div>

                <!-- Нижний блок: 6 содержаний -->
                <div class="paradigm-content-grid">
                    <div class="paradigm-item" data-content="silence" data-description="Тишина и созерцание">
                        <span class="paradigm-item-icon">😌</span>
                        <span class="paradigm-item-label has-manrope-font-family">Тишина</span>
                    </div>
                    <div class="paradigm-item" data-content="energy" data-description="Энергия и движение">
                        <span class="paradigm-item-icon">⚡</span>
                        <span class="paradigm-item-label has-manrope-font-family">Энергия</span>
                    </div>
                    <div class="paradigm-item" data-content="thought" data-description="Мысль и рефлексия">
                        <span class="paradigm-item-icon">🤔</span>
                        <span class="paradigm-item-label has-manrope-font-family">Мысль</span>
                    </div>
                    <div class="paradigm-item" data-content="drama" data-description="Драма и напряжение">
                        <span class="paradigm-item-icon">🎭</span>
                        <span class="paradigm-item-label has-manrope-font-family">Драма</span>
                    </div>
                    <div class="paradigm-item" data-content="chaos" data-description="Хаос и случайность">
                        <span class="paradigm-item-icon">🌀</span>
                        <span class="paradigm-item-label has-manrope-font-family">Хаос</span>
                    </div>
                    <div class="paradigm-item" data-content="memory" data-description="Память и время">
                        <span class="paradigm-item-icon">🕰️</span>
                        <span class="paradigm-item-label has-manrope-font-family">Память</span>
                    </div>
                </div>

            </div>

            <!-- Всплывающая подсказка -->
            <div class="paradigm-tooltip" id="paradigmTooltip">
                <div class="tooltip-content">
                    <h4 class="tooltip-title has-spectral-font-family">Пример работы</h4>
                    <p class="tooltip-description has-manrope-font-family"></p>
                    <div class="tooltip-examples">
                        <span class="tooltip-badge has-jetbrains-mono-font-family">📖 Текст</span>
                        <span class="tooltip-badge has-jetbrains-mono-font-family">😌 Тишина</span>
                    </div>
                </div>
            </div>

        </div>
        <!-- /wp:group -->

        <!-- Поясняющий текст -->
        <!-- wp:paragraph {"align":"center","className":"paradigm-description","fontFamily":"manrope"} -->
        <p class="has-text-align-center paradigm-description has-manrope-font-family">
            Наведите на любой элемент, чтобы увидеть примеры работ<br>
            <small style="font-size:0.8em;">Каждая работа — это пересечение формы и содержания</small>
        </p>
        <!-- /wp:paragraph -->

    </div>

</div>
<!-- /wp:group -->