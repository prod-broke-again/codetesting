<template>
    <div class="dashboard-container">
        <!-- Навигация -->
        <Navigation :user="user" />
        
        <!-- Основной контент -->
        <div class="dashboard-content">
            <!-- Заголовок -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    {{ title }}
                </h1>
                <p class="dashboard-description">
                    {{ description }}
                </p>
            </div>

            <!-- Статистика -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-blue">
                            <svg class="stat-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Всего сниппетов</p>
                            <p class="stat-value">{{ stats.total_snippets }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-green">
                            <svg class="stat-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Публичные</p>
                            <p class="stat-value">{{ stats.public_snippets }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-purple">
                            <svg class="stat-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Приватные</p>
                            <p class="stat-value">{{ stats.private_snippets }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-orange">
                            <svg class="stat-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">По ссылке</p>
                            <p class="stat-value">{{ stats.unlisted_snippets }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-red">
                            <svg class="stat-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Зашифрованные</p>
                            <p class="stat-value">{{ stats.encrypted_snippets }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-teal">
                            <svg class="stat-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Просмотров</p>
                            <p class="stat-value">{{ stats.total_views }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Последние сниппеты -->
            <div class="sections-grid">
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">Последние сниппеты</h2>
                        <Link href="/my-snippets" class="section-link">Посмотреть все</Link>
                    </div>
                    <div class="snippets-list">
                        <div v-if="recentSnippets && recentSnippets.length > 0">
                            <div v-for="snippet in recentSnippets" :key="snippet.id" class="snippet-item">
                                <div class="snippet-info">
                                    <h3 class="snippet-title">
                                        <Link :href="`/code/${snippet.hash}`" class="snippet-link">
                                            {{ snippet.content.substring(0, 30) }}...
                                        </Link>
                                    </h3>
                                    <div class="snippet-meta">
                                        <span class="snippet-language">{{ LANGUAGE_OPTIONS[snippet.language as keyof typeof LANGUAGE_OPTIONS] || snippet.language }}</span>
                                        <span class="snippet-date">{{ formatDate(snippet.created_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="empty-section">
                            <p>У вас пока нет сниппетов</p>
                            <Link href="/"><ButtonPrimary>Создать первый</ButtonPrimary></Link>
                        </div>
                    </div>
                </div>

                <!-- Популярные сниппеты -->
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">Популярные сниппеты</h2>
                        <Link href="/my-snippets" class="section-link">Посмотреть все</Link>
                    </div>
                    <div class="snippets-list">
                        <div v-if="popularSnippets && popularSnippets.length > 0">
                            <div v-for="snippet in popularSnippets" :key="snippet.id" class="snippet-item">
                                <div class="snippet-info">
                                    <h3 class="snippet-title">
                                        <Link :href="`/code/${snippet.hash}`" class="snippet-link">
                                            {{ snippet.content.substring(0, 30) }}...
                                        </Link>
                                    </h3>
                                    <div class="snippet-meta">
                                        <span class="snippet-language">{{ LANGUAGE_OPTIONS[snippet.language as keyof typeof LANGUAGE_OPTIONS] || snippet.language }}</span>
                                        <span class="snippet-views">{{ snippet.access_count }} просмотров</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="empty-section">
                            <p>Нет популярных сниппетов</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Футер -->
        <Footer />
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { LANGUAGE_OPTIONS } from '@/types';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import { Link } from '@inertiajs/vue3';
import ButtonPrimary from '@/components/buttons/ButtonPrimary.vue';

// Props от Inertia.js
interface Props {
    stats: any;
    recentSnippets: any[];
    popularSnippets: any[];
    title: string;
    description: string;
    user?: any;
}
const props = defineProps<Props>();

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<style scoped>
.dashboard-container {
    min-height: 100vh;
    background: var(--gradient-background);
    display: flex;
    flex-direction: column;
}

.dashboard-content {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
    padding-top: 3rem;
    padding-bottom: 3rem;
    flex: 1;
}

@media (min-width: 640px) {
    .dashboard-content {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .dashboard-content {
        padding: 0 2rem;
    }
}

.dashboard-header {
    text-align: center;
    margin-bottom: 3rem;
}

.dashboard-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

@media (min-width: 768px) {
    .dashboard-title {
        font-size: 3.75rem;
    }
}

.dashboard-description {
    font-size: 1.25rem;
    color: var(--color-textSecondary);
    max-width: 48rem;
    margin: 0 auto;
    line-height: 1.6;
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 3rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.stat-card {
    background-color: var(--color-surface);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid var(--color-border);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.stat-content {
    display: flex;
    align-items: center;
}

.stat-icon {
    padding: 0.75rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon-blue {
    background-color: rgba(59, 130, 246, 0.1);
    color: var(--color-primary);
}

.stat-icon-green {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--color-success);
}

.stat-icon-purple {
    background-color: rgba(139, 92, 246, 0.1);
    color: var(--color-secondary);
}

.stat-icon-orange {
    background-color: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stat-icon-red {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.stat-icon-teal {
    background-color: rgba(20, 184, 166, 0.1);
    color: #14b8a6;
}

.stat-svg {
    width: 1.5rem;
    height: 1.5rem;
}

.stat-info {
    margin-left: 1rem;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-textSecondary);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text);
}

.sections-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 1024px) {
    .sections-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.section-card {
    background-color: var(--color-surface);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid var(--color-border);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text);
}

.section-link {
    color: var(--color-primary);
    text-decoration: none;
    font-size: 0.875rem;
}

.section-link:hover {
    text-decoration: underline;
}

.snippets-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.snippet-item {
    padding: 1rem;
    background-color: var(--color-surface);
    border-radius: 0.5rem;
    border: 1px solid var(--color-border);
}

.snippet-title {
    font-size: 1rem;
    font-weight: 500;
    color: var(--color-text);
    margin-bottom: 0.5rem;
}

.snippet-link {
    color: var(--color-primary);
    text-decoration: none;
}

.snippet-link:hover {
    text-decoration: underline;
}

.snippet-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--color-textSecondary);
}

.empty-section {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--color-textSecondary);
}

.btn-primary {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: var(--gradient-primary);
    color: white;
    text-decoration: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
</style> 