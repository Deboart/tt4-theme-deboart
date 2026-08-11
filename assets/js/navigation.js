/**
 * Deboart - Навигация и мобильное меню
 * Версия 2.2 (двойной клик для подменю на мобильных)
 */

(function() {
    'use strict';

    class DeboartNavigation {
        constructor() {
            this.menuToggle = document.querySelector('.menu-toggle');
            this.menuClose = null;
            this.primaryMenu = document.querySelector('.primary-menu');
            this.mobileMenu = document.querySelector('.mobile-nav .mobile-primary-menu');
            this.body = document.body;
            this.menuLinks = document.querySelectorAll('.primary-menu a, .mobile-nav a');
            
            this.init();
        }
        
        init() {
            if (!this.primaryMenu && !this.mobileMenu) return;
            
            this.createToggleButton();
            this.addEventListeners();
            this.setupDropdowns();
            this.checkScreenSize();
            
            window.addEventListener('resize', () => this.checkScreenSize());
        }
        
        createToggleButton() {
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
        
        createCloseButton() {
            if (this.menuClose) return;
            
            const close = document.createElement('button');
            close.className = 'menu-close';
            close.innerHTML = '✕';
            close.setAttribute('aria-label', 'Закрыть меню');
            
            close.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeMenu();
            });
            
            if (this.primaryMenu) {
                this.primaryMenu.prepend(close);
                this.menuClose = close;
            }
        }
        
        removeCloseButton() {
            if (this.menuClose && this.menuClose.parentNode) {
                this.menuClose.parentNode.removeChild(this.menuClose);
                this.menuClose = null;
            }
        }
        
        addEventListeners() {
            if (this.menuToggle) {
                this.menuToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.openMenu();
                });
            }
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isMenuOpen()) {
                    this.closeMenu();
                }
            });
            
            this.menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768 && this.isMenuOpen()) {
                        this.closeMenu();
                    }
                });
            });
            
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && this.isMenuOpen()) {
                    const isClickInside = this.primaryMenu?.contains(e.target) || 
                                         this.mobileMenu?.contains(e.target) ||
                                         this.menuToggle?.contains(e.target);
                    
                    if (!isClickInside) {
                        this.closeMenu();
                    }
                }
            });
        }
        
        setupDropdowns() {
            // ===== ДЛЯ ДЕСКТОПНОГО МЕНЮ =====
            const desktopDropdowns = document.querySelectorAll('.primary-menu .menu-item-has-children');
            
            desktopDropdowns.forEach(item => {
                const link = item.querySelector('a');
                if (link) {
                    link.addEventListener('click', (e) => {
                        if (window.innerWidth <= 768) {
                            e.preventDefault();
                            // Двойной клик: если уже открыто — переходим по ссылке
                            if (item.classList.contains('active')) {
                                window.location.href = link.href;
                            } else {
                                // Закрываем другие открытые подменю
                                desktopDropdowns.forEach(other => {
                                    if (other !== item) other.classList.remove('active');
                                });
                                item.classList.toggle('active');
                            }
                        }
                    });
                }
            });
            
            // ===== ДЛЯ МОБИЛЬНОГО МЕНЮ =====
            const mobileDropdowns = document.querySelectorAll('.mobile-nav .menu-item-has-children');
            
            mobileDropdowns.forEach(item => {
                const link = item.querySelector('a');
                if (link) {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        // Двойной клик: если уже открыто — переходим по ссылке
                        if (item.classList.contains('active')) {
                            window.location.href = link.href;
                        } else {
                            // Закрываем другие открытые подменю
                            mobileDropdowns.forEach(other => {
                                if (other !== item) other.classList.remove('active');
                            });
                            item.classList.toggle('active');
                        }
                    });
                }
            });
        }
        
        openMenu() {
            this.createCloseButton();
            
            this.primaryMenu?.classList.add('is-open');
            this.body.classList.add('is-menu-open');
            if (this.menuToggle) {
                this.menuToggle.setAttribute('aria-expanded', 'true');
            }
            
            this.body.style.overflow = 'hidden';
        }
        
        closeMenu() {
            this.primaryMenu?.classList.remove('is-open');
            this.body.classList.remove('is-menu-open');
            if (this.menuToggle) {
                this.menuToggle.setAttribute('aria-expanded', 'false');
            }
            
            this.body.style.overflow = '';
            
            document.querySelectorAll('.primary-menu .menu-item-has-children.active').forEach(item => {
                item.classList.remove('active');
            });
            
            document.querySelectorAll('.mobile-nav .menu-item-has-children.active').forEach(item => {
                item.classList.remove('active');
            });
            
            this.removeCloseButton();
        }
        
        isMenuOpen() {
            return this.primaryMenu?.classList.contains('is-open') || false;
        }
        
        checkScreenSize() {
            if (window.innerWidth > 768) {
                if (this.isMenuOpen()) {
                    this.closeMenu();
                }
                this.removeCloseButton();
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new DeboartNavigation();
        });
    } else {
        new DeboartNavigation();
    }
})();