/**
 * AJAX фильтрация работ для Deboart
 */
(function($) {
    'use strict';
    
    // Конфигурация
    var config = {
        form: '#works-filter-form',
        container: '#works-results-container',
        countElement: '#filtered-count',
        resetButton: '#reset-filters',
        emptyResetButton: '#reset-from-empty',
        searchInput: '#search',
        searchClear: '.search-clear',
        accordionToggle: '#accordion-toggle',
        accordionContent: '#accordion-content'
    };
    
    // Состояние
    var state = {
        isFiltering: false,
        currentRequest: null,
        timeout: null,
        debounceDelay: 300
    };
    
    // Инициализация
    function init() {
        bindEvents();
        initAccordion();
    }
    
    // Привязка событий
    function bindEvents() {
        // Изменение фильтров
        $(document).on('change', config.form + ' input, ' + config.form + ' select', debouncedFilter);
        
        // Поиск с debounce
        $(config.searchInput).on('input', debouncedFilter);
        
        // Очистка поиска
        $(document).on('click', config.searchClear, clearSearch);
        
        // Сброс фильтров
        $(document).on('click', config.resetButton, resetFilters);
        $(document).on('click', config.emptyResetButton, resetFilters);
        
        // Пагинация (делегирование)
        $(document).on('click', '.works-pagination a', handlePagination);
        
        // Обработка Enter в поиске
        $(config.searchInput).on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                filterWorks();
            }
        });
    }
    
    // Debounce для поиска
    function debouncedFilter() {
        clearTimeout(state.timeout);
        state.timeout = setTimeout(filterWorks, state.debounceDelay);
    }
    
    // Основная функция фильтрации
    function filterWorks(page) {
        if (state.isFiltering && state.currentRequest) {
            state.currentRequest.abort();
        }
        
        // Показываем индикатор загрузки
        showLoading();
        
        // Собираем данные формы
        var formData = $(config.form).serialize();
        
        // Добавляем номер страницы если указан
        if (page) {
            formData += '&paged=' + page;
        }
        
        // AJAX запрос
        state.currentRequest = $.ajax({
            url: deboart_ajax.ajax_url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateResults(response.data);
                } else {
                    showError('Ошибка при загрузке данных');
                }
            },
            error: function(xhr, status, error) {
                if (status !== 'abort') {
                    showError('Ошибка соединения. Попробуйте еще раз.');
                }
            },
            complete: function() {
                hideLoading();
                state.isFiltering = false;
                state.currentRequest = null;
            }
        });
        
        state.isFiltering = true;
    }
    
    // Обновление результатов
    function updateResults(data) {
        $(config.container).html(data.html);
        $(config.countElement).text(data.count);
        
        // Обновляем URL в браузере без перезагрузки
        updateBrowserURL();
        
        // Прокрутка к результатам на мобильных
        if ($(window).width() < 768) {
            $('html, body').animate({
                scrollTop: $(config.container).offset().top - 100
            }, 300);
        }
    }
    
    // Очистка поиска
    function clearSearch() {
        $(config.searchInput).val('').trigger('input').focus();
    }
    
    // Сброс всех фильтров
    function resetFilters() {
        // Очищаем все чекбоксы
        $(config.form + ' input[type="checkbox"]').prop('checked', false);
        
        // Сбрасываем селекты
        $(config.form + ' select').val('');
        
        // Очищаем поиск
        $(config.searchInput).val('');
        
        // Запускаем фильтрацию
        filterWorks(1);
    }
    
    // Обработка пагинации
    function handlePagination(e) {
        e.preventDefault();
        
        var $link = $(this);
        var href = $link.attr('href');
        var page = 1;
        
        // Извлекаем номер страницы из URL
        var match = href.match(/paged?=(\d+)/);
        if (match) {
            page = parseInt(match[1]);
        }
        
        filterWorks(page);
    }
    
    // Обновление URL в браузере
    function updateBrowserURL() {
        var params = $(config.form).serialize();
        var url = window.location.pathname + '?' + params;
        
        if (history.pushState) {
            history.pushState(null, '', url);
        }
    }
    

// Аккордеон для дополнительных фильтров
function initAccordion() {
    $(config.accordionToggle).on('click', function() {
        var $this = $(this);
        var $content = $(config.accordionContent);
        var $arrow = $this.find('.toggle-arrow');
        
        // Переключаем классы
        $this.toggleClass('active');
        $content.toggleClass('active');
        
        // Меняем стрелку
        if ($this.hasClass('active')) {
            $arrow.text('▲');
        } else {
            $arrow.text('▼');
        }
        
        // Сохраняем состояние в localStorage
        localStorage.setItem('deboart_filters_open', $this.hasClass('active'));
    });
    
    // Восстанавливаем состояние из localStorage
    var savedState = localStorage.getItem('deboart_filters_open');
    if (savedState === 'true') {
        $(config.accordionToggle).addClass('active');
        $(config.accordionContent).addClass('active');
        $(config.accordionToggle + ' .toggle-arrow').text('▲');
    }
}
    
    // Показать индикатор загрузки
    function showLoading() {
        $(config.container).addClass('loading');
        
        var loader = $('<div class="ajax-loader"><div class="loader-spinner"></div></div>');
        $(config.container).append(loader);
    }
    
    // Скрыть индикатор загрузки
    function hideLoading() {
        $(config.container).removeClass('loading');
        $(config.container).find('.ajax-loader').remove();
    }
    
    // Показать ошибку
    function showError(message) {
        var errorHtml = '<div class="ajax-error"><div class="error-icon">⚠️</div><p>' + message + '</p></div>';
        $(config.container).html(errorHtml);
    }
    
    // Инициализация при загрузке документа
    $(document).ready(init);
    
})(jQuery);