<?php
/**
 * Title: Deboart Contact Section
 * Slug: deboart/contact-section
 * Categories: featured
 * Description: Секция "Контакты" - форма связи (готово для Contact Form 7)
 */
?>
<!-- wp:group {"align":"full","className":"deboart-contact-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|huge","bottom":"var:preset|spacing|huge"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull deboart-contact-section has-primary-dark-background-color has-background" style="padding-top:var(--wp--preset--spacing--huge);padding-bottom:var(--wp--preset--spacing--huge);">

    <div class="wp-block-group__inner-container">

        <!-- Заголовок секции - ОБНОВЛЕН -->
        <!-- wp:heading {"textAlign":"center","className":"contact-heading","fontFamily":"spectral","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|large"}}}} -->
        <h2 class="wp-block-heading has-text-align-center contact-heading has-spectral-font-family" style="margin-bottom:var(--wp--preset--spacing--large);">
            ВАШ СЛЕДУЮЩИЙ ВОПРОС — КАКОЙ?
        </h2>
        <!-- /wp:heading -->

        <!-- Подзаголовок - ОБНОВЛЕН -->
        <!-- wp:paragraph {"align":"center","className":"contact-subtitle","fontFamily":"manrope","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|x-large"}}}} -->
        <p class="has-text-align-center contact-subtitle has-manrope-font-family" style="margin-bottom:var(--wp--preset--spacing--x-large);">
            Каждое исследование начинается с вопроса. Расскажите о вашей идее, проекте или просто поделитесь мыслью — это может стать началом следующего эксперимента.
        </p>
        <!-- /wp:paragraph -->

        <!-- Контейнер формы -->
        <!-- wp:group {"className":"contact-form-container","layout":{"type":"constrained"}} -->
        <div class="wp-block-group contact-form-container">

            <!-- СТАТИЧЕСКАЯ ФОРМА (будет заменена на Contact Form 7) -->
            <form class="deboart-contact-form" id="deboartStaticContactForm">

                <!-- Группа: Имя -->
                <div class="contact-form-group">
                    <label for="contact-name" class="contact-form-label has-spectral-font-family">Ваше имя</label>
                    <input type="text"
                           id="contact-name"
                           name="contact-name"
                           class="contact-form-input"
                           placeholder="Как к вам обращаться?"
                           required>
                </div>

                <!-- Группа: Email -->
                <div class="contact-form-group">
                    <label for="contact-email" class="contact-form-label has-spectral-font-family">Email для ответа</label>
                    <input type="email"
                           id="contact-email"
                           name="contact-email"
                           class="contact-form-input"
                           placeholder="example@domain.com"
                           required>
                </div>

                <!-- Группа: Тип запроса -->
                <div class="contact-form-group">
                    <label for="contact-type" class="contact-form-label has-spectral-font-family">Тип запроса</label>
                    <select id="contact-type" name="contact-type" class="contact-form-select" required>
                        <option value="" disabled selected>Выберите тип обращения</option>
                        <option value="collaboration">Предложение о коллаборации</option>
                        <option value="commission">Запрос на комиссию (персональное исследование)</option>
                        <option value="interview">Приглашение выступить / дать интервью</option>
                        <option value="question">Вопрос о методе или работе</option>
                        <option value="other">Другое</option>
                    </select>
                </div>

                <!-- Группа: Сообщение -->
                <div class="contact-form-group">
                    <label for="contact-message" class="contact-form-label has-spectral-font-family">Ваш вопрос или идея</label>
                    <textarea id="contact-message"
                              name="contact-message"
                              class="contact-form-textarea"
                              placeholder="Опишите вашу идею, проект или задайте вопрос. Что вас интересует? Какие границы хочется исследовать?"
                              rows="6"
                              required></textarea>
                </div>

                <!-- Кнопка отправки - ОБНОВЛЕН -->
                <button type="submit" class="contact-submit-button has-manrope-font-family">
                    ОТПРАВИТЬ ВОПРОС
                </button>

            </form>

            <!-- Примечание о Contact Form 7 - ОБНОВЛЕН -->
            <!-- wp:paragraph {"align":"center","className":"contact-form-note","fontFamily":"jetbrains-mono"} -->
            <p class="has-text-align-center contact-form-note has-jetbrains-mono-font-family">
                ⚡ <strong>Внимание:</strong> Это статическая форма-заглушка.<br>
                После установки плагина <strong>Contact Form 7</strong> форма будет заменена на рабочую с защитой от спама.
            </p>
            <!-- /wp:paragraph -->

        </div>
        <!-- /wp:group -->

        <!-- Нижняя навигация -->
        <!-- wp:group {"className":"contact-bottom-navigation","layout":{"type":"constrained"}} -->
        <div class="wp-block-group contact-bottom-navigation">

            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">
                <!-- wp:group {"className":"contact-nav-links","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
                <div class="wp-block-group contact-nav-links">
                    <a href="/" class="contact-nav-link active has-spectral-font-family">DEBO</a>
                    <a href="/works/" class="contact-nav-link has-spectral-font-family">ИССЛЕДОВАНИЯ</a>
                    <a href="/lab/" class="contact-nav-link has-spectral-font-family">ЛАБОРАТОРИЯ</a>
                    <a href="/protocols/" class="contact-nav-link has-spectral-font-family">ПРОТОКОЛЫ</a>
                    <a href="/contact/" class="contact-nav-link has-spectral-font-family">СЕАНС</a>
                </div>
                <!-- /wp:group -->
            </p>
            <!-- /wp:paragraph -->

            <!-- Копирайт - ОБНОВЛЕН -->
            <!-- wp:paragraph {"align":"center","className":"contact-copyright","fontFamily":"jetbrains-mono"} -->
            <p class="has-text-align-center contact-copyright has-jetbrains-mono-font-family">
                Все исследования защищены концепцией.<br>
                Этот сайт — часть продолжающегося эксперимента.
            </p>
            <!-- /wp:paragraph -->

        </div>
        <!-- /wp:group -->

    </div>

</div>
<!-- /wp:group -->

<!-- JavaScript для статической формы (предупреждение) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const staticForm = document.getElementById('deboartStaticContactForm');

    if (staticForm) {
        staticForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Показываем уведомление вместо реальной отправки
            alert('📝 Это демонстрационная форма.\n\nДля реальной работы установите плагин Contact Form 7 и замените эту форму на шорткод [contact-form-7].\n\nФорма готова к интеграции — все стили уже настроены.');

            // Сбрасываем форму
            staticForm.reset();
        });
    }
});
</script>