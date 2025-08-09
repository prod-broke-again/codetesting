<template>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="footer-logo">CT</div>
                    <p class="footer-description">Безопасный обмен кодом</p>
                    <div class="footer-socials">
                        <a href="#" class="social-link" title="GitHub">GitHub</a>
                        <a href="#" class="social-link" title="Twitter">Twitter</a>
                        <a href="#" class="social-link" title="Discord">Discord</a>
                    </div>
                </div>

                <div class="footer-links">
                    <div class="footer-column">
                        <h4 class="footer-heading">Продукт</h4>
                        <ul>
                            <li><Link href="/explore" class="footer-link">Обзор сниппетов</Link></li>
                            <li><Link href="/trending" class="footer-link">Популярное</Link></li>
                            <li><Link href="/languages" class="footer-link">Языки программирования</Link></li>
                            <li><Link href="/themes" class="footer-link">Темы оформления</Link></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4 class="footer-heading">Разработчикам</h4>
                        <ul>
                            <li><Link href="/api" class="footer-link">API документация</Link></li>
                            <li><Link href="/sdk" class="footer-link">SDK</Link></li>
                            <li><Link href="/plugins" class="footer-link">Плагины</Link></li>
                            <li><Link href="/integrations" class="footer-link">Интеграции</Link></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4 class="footer-heading">Поддержка</h4>
                        <ul>
                            <li><Link href="/help" class="footer-link">Помощь</Link></li>
                            <li><Link href="/docs" class="footer-link">Документация</Link></li>
                            <li><Link href="/contact" class="footer-link">Контакты</Link></li>
                            <li><Link href="/status" class="footer-link">Статус сервиса</Link></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4 class="footer-heading">Компания</h4>
                        <ul>
                            <li><Link href="/about" class="footer-link">О нас</Link></li>
                            <li><Link href="/blog" class="footer-link">Блог</Link></li>
                            <li><Link href="/careers" class="footer-link">Карьера</Link></li>
                            <li><Link href="/press" class="footer-link">Пресса</Link></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copy">© {{ new Date().getFullYear() }} CodeTesting.ru</p>
                <div class="footer-legal">
                    <Link href="/privacy" class="footer-legal-link">Политика конфиденциальности</Link>
                    <Link href="/terms" class="footer-legal-link">Условия использования</Link>
                    <Link href="/cookies" class="footer-legal-link">Cookies</Link>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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