<template>
    <footer class="footer-container">
        <div class="footer-content">
            <div class="footer-main">
                <!-- Логотип и описание -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <svg class="footer-logo-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="footer-logo-text">CodeTesting.ru</span>
                    </div>
                    <p class="footer-description">
                        Безопасная платформа для создания и обмена кодом. Поддерживает 30+ языков программирования с умным определением языка.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link" title="GitHub">
                            <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" title="Discord">
                            <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419-.019 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1568 2.4189Z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Ссылки -->
                <div class="footer-links">
                    <div class="footer-section">
                        <h3 class="footer-section-title">Платформа</h3>
                        <ul class="footer-section-links">
                            <li><a href="/explore" class="footer-link">Обзор сниппетов</a></li>
                            <li><a href="/trending" class="footer-link">Популярное</a></li>
                            <li><a href="/languages" class="footer-link">Языки программирования</a></li>
                            <li><a href="/themes" class="footer-link">Темы оформления</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3 class="footer-section-title">Разработка</h3>
                        <ul class="footer-section-links">
                            <li><a href="/api" class="footer-link">API документация</a></li>
                            <li><a href="/sdk" class="footer-link">SDK</a></li>
                            <li><a href="/plugins" class="footer-link">Плагины</a></li>
                            <li><a href="/integrations" class="footer-link">Интеграции</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3 class="footer-section-title">Поддержка</h3>
                        <ul class="footer-section-links">
                            <li><a href="/help" class="footer-link">Помощь</a></li>
                            <li><a href="/docs" class="footer-link">Документация</a></li>
                            <li><a href="/contact" class="footer-link">Контакты</a></li>
                            <li><a href="/status" class="footer-link">Статус сервиса</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3 class="footer-section-title">Компания</h3>
                        <ul class="footer-section-links">
                            <li><a href="/about" class="footer-link">О нас</a></li>
                            <li><a href="/blog" class="footer-link">Блог</a></li>
                            <li><a href="/careers" class="footer-link">Карьера</a></li>
                            <li><a href="/press" class="footer-link">Пресса</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Нижняя часть футера -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="footer-copyright">
                        <p>&copy; 2024 CodeTesting.ru. Все права защищены.</p>
                    </div>
                    <div class="footer-legal">
                        <a href="/privacy" class="footer-legal-link">Политика конфиденциальности</a>
                        <a href="/terms" class="footer-legal-link">Условия использования</a>
                        <a href="/cookies" class="footer-legal-link">Cookies</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
// Компонент футера не требует дополнительной логики
</script>

<style scoped>
.footer-container {
    background-color: var(--color-surface);
    border-top: 1px solid var(--color-border);
    margin-top: auto;
}

.footer-content {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
}

@media (min-width: 640px) {
    .footer-content {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .footer-content {
        padding: 0 2rem;
    }
}

.footer-main {
    padding: 3rem 0 2rem;
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 768px) {
    .footer-main {
        grid-template-columns: 2fr 3fr;
        gap: 3rem;
    }
}

@media (min-width: 1024px) {
    .footer-main {
        grid-template-columns: 1fr 2fr;
        gap: 4rem;
    }
}

.footer-brand {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.footer-logo-icon {
    width: 2rem;
    height: 2rem;
    background: var(--gradient-primary);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.footer-logo-svg {
    width: 1.25rem;
    height: 1.25rem;
    color: white;
}

.footer-logo-text {
    font-size: 1.25rem;
    font-weight: 700;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.footer-description {
    color: var(--color-textSecondary);
    line-height: 1.6;
    max-width: 24rem;
}

.footer-social {
    display: flex;
    gap: 0.75rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background-color: var(--color-border);
    color: var(--color-textSecondary);
    transition: all 0.2s;
}

.social-link:hover {
    background-color: var(--color-primary);
    color: white;
    transform: translateY(-2px);
}

.social-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.footer-links {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

@media (min-width: 768px) {
    .footer-links {
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
}

.footer-section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.footer-section-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.footer-link {
    color: var(--color-textSecondary);
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.footer-link:hover {
    color: var(--color-primary);
}

.footer-bottom {
    border-top: 1px solid var(--color-border);
    padding: 1.5rem 0;
}

.footer-bottom-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
    text-align: center;
}

@media (min-width: 768px) {
    .footer-bottom-content {
        flex-direction: row;
        justify-content: space-between;
        text-align: left;
    }
}

.footer-copyright {
    color: var(--color-textSecondary);
    font-size: 0.875rem;
}

.footer-legal {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

@media (min-width: 768px) {
    .footer-legal {
        justify-content: flex-end;
    }
}

.footer-legal-link {
    color: var(--color-textSecondary);
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.footer-legal-link:hover {
    color: var(--color-primary);
}
</style> 