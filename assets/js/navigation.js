/**
 * Deboart - Навигация и мобильное меню
 * Версия 2.0 (кастомная реализация)
 */

(function() {
    'use strict';

    class DeboartNavigation {
        constructor() {
            this.menuToggle = document.querySelector('.menu-toggle');
            this.menuClose = null; // Кнопка закрытия создается только при открытии
            this.primaryMenu = document.querySelector('.primary-menu');
            this.body = document.body;
            this.menuLinks = document.querySelectorAll('.primary-menu a');
            
            this.init();
        }
        
        init() {
            if (!this.primaryMenu) return;
            
            // Создаем кнопку бургера, если её нет
            this.createToggleButton();
            
            // Добавляем обработчики
            this.addEventListeners();
            
            // Обработка выпадающих пунктов на мобильных
            this.setupDropdowns();
            
            // Начальное состояние
            this.checkScreenSize();
            
            // Следим за изменением размера экрана
            window.addEventListener('resize', () => this.checkScreenSize());
        }
        
        /**
         * Создает только кнопку бургера
         * Кнопка закрытия создается динамически при открытии меню
         */
        createToggleButton() {
            // Кнопка открытия (бургер)
            if (!this.menuToggle) {
                const toggle = document.createElement('button');
                toggle.className = 'menu-toggle';
                toggle.innerHTML = `
                    <span class="menu-toggle-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                `;
                toggle.setAttribute('aria-label', 'Открыть меню');
                toggle.setAttribute('aria-expanded', 'false');
                
                const nav = document.querySelector('.deboart-navigation');
                if (nav) {
                    nav.prepend(toggle);
                    this.menuToggle = toggle;
                }
            }
        }
        
        /**
         * Создает кнопку закрытия при открытии меню
         */
        createCloseButton() {
            if (this.menuClose) return; // Если уже есть, не создаем
            
            const close = document.createElement('button');
            close.className = 'menu-close';
            close.innerHTML = '✕';
            close.setAttribute('aria-label', 'Закрыть меню');
            
            // Добавляем обработчик закрытия
            close.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeMenu();
            });
            
            // Вставляем в начало меню
            if (this.primaryMenu) {
                this.primaryMenu.prepend(close);
                this.menuClose = close;
            }
        }
        
        /**
         * Удаляет кнопку закрытия из DOM
         */
        removeCloseButton() {
            if (this.menuClose && this.menuClose.parentNode) {
                this.menuClose.parentNode.removeChild(this.menuClose);
                this.menuClose = null;
            }
        }
        
        addEventListeners() {
            // Открытие меню по клику на бургер
            if (this.menuToggle) {
                this.menuToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.openMenu();
                });
            }
            
            // Закрытие по ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isMenuOpen()) {
                    this.closeMenu();
                }
            });
            
            // Закрытие по клику на ссылку
            this.menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768 && this.isMenuOpen()) {
                        this.closeMenu();
                    }
                });
            });
            
            // Закрытие по клику вне меню
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && this.isMenuOpen()) {
                    const isClickInside = this.primaryMenu?.contains(e.target) || 
                                         this.menuToggle?.contains(e.target);
                    
                    if (!isClickInside) {
                        this.closeMenu();
                    }
                }
            });
        }
        
        setupDropdowns() {
            // Для мобильных: клик по родительскому пункту открывает подменю
            const dropdownItems = document.querySelectorAll('.primary-menu .has-children');
            
            dropdownItems.forEach(item => {
                const link = item.querySelector('a');
                
                if (link) {
                    link.addEventListener('click', (e) => {
                        if (window.innerWidth <= 768) {
                            e.preventDefault();
                            item.classList.toggle('open');
                        }
                    });
                }
            });
        }
        
        openMenu() {
            // Создаем кнопку закрытия при открытии
            this.createCloseButton();
            
            this.primaryMenu?.classList.add('is-open');
            this.body.classList.add('is-menu-open');
            if (this.menuToggle) {
                this.menuToggle.setAttribute('aria-expanded', 'true');
            }
            
            // Блокируем скролл
            this.body.style.overflow = 'hidden';
        }
        
        closeMenu() {
            this.primaryMenu?.classList.remove('is-open');
            this.body.classList.remove('is-menu-open');
            if (this.menuToggle) {
                this.menuToggle.setAttribute('aria-expanded', 'false');
            }
            
            // Возвращаем скролл
            this.body.style.overflow = '';
            
            // Закрываем все открытые дропдауны
            document.querySelectorAll('.primary-menu .has-children.open').forEach(item => {
                item.classList.remove('open');
            });
            
            // Удаляем кнопку закрытия из DOM
            this.removeCloseButton();
        }
        
        isMenuOpen() {
            return this.primaryMenu?.classList.contains('is-open') || false;
        }
        
        checkScreenSize() {
            if (window.innerWidth > 768) {
                // На десктопе меню всегда видимо и без кнопки закрытия
                if (this.isMenuOpen()) {
                    this.closeMenu();
                }
                this.removeCloseButton();
            }
        }
    }

    // Инициализация после загрузки DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new DeboartNavigation();
        });
    } else {
        new DeboartNavigation();
    }
})();