<template>
    <div class="my-snippets-container">
        <!-- Навигация -->
        <Navigation :user="user" />
        
        <!-- Основной контент -->
        <div class="my-snippets-content">
            <!-- Заголовок -->
            <div class="my-snippets-header">
                <h1 class="my-snippets-title">
                    {{ title }}
                </h1>
                <p class="my-snippets-description">
                    {{ description }}
                </p>
            </div>

            <!-- Фильтры -->
            <div class="filters-section">
                <div class="filters-grid">
                    <!-- Поиск -->
                    <div class="filter-item">
                        <input
                            v-model="searchQuery"
                            @input="onSearchInput"
                            type="text"
                            placeholder="Поиск сниппетов..."
                            class="filter-input"
                        />
                    </div>

                    <!-- Приватность -->
                    <div class="filter-item">
                        <select v-model="selectedPrivacy" @change="applyFilters" class="filter-select">
                            <option value="">Все типы</option>
                            <option value="private">🔒 Приватные</option>
                            <option value="unlisted">🔗 По ссылке</option>
                            <option value="public">🌐 Публичные</option>
                        </select>
                    </div>

                    <!-- Язык -->
                    <div class="filter-item">
                        <select v-model="selectedLanguage" @change="applyFilters" class="filter-select">
                            <option value="">Все языки</option>
                            <option v-for="(label, value) in LANGUAGE_OPTIONS" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>

                    <!-- Сортировка -->
                    <div class="filter-item">
                        <select v-model="selectedSort" @change="applyFilters" class="filter-select">
                            <option value="latest">Сначала новые</option>
                            <option value="popular">По популярности</option>
                            <option value="oldest">Сначала старые</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Список сниппетов -->
            <div class="snippets-grid">
                <div v-if="snippets.data && snippets.data.length > 0" class="snippets-list">
                    <div v-for="snippet in snippets.data" :key="snippet.id" class="snippet-card">
                        <div class="snippet-header">
                            <div class="snippet-info">
                                <h3 class="snippet-title">
                                    <a :href="`/code/${snippet.hash}`" class="snippet-link">
                                        {{ snippet.content.substring(0, 50) }}...
                                    </a>
                                </h3>
                                <div class="snippet-meta">
                                    <span class="snippet-language">{{ LANGUAGE_OPTIONS[snippet.language as keyof typeof LANGUAGE_OPTIONS] || snippet.language }}</span>
                                    <span class="snippet-privacy" :class="`privacy-${snippet.privacy}`">
                                        {{ getPrivacyLabel(snippet.privacy) }}
                                    </span>
                                    <span class="snippet-date">{{ formatDate(snippet.created_at) }}</span>
                                    <span class="snippet-views">{{ snippet.access_count }} просмотров</span>
                                </div>
                            </div>
                            <div class="snippet-actions">
                                <a :href="`/code/${snippet.hash}`" class="btn-secondary">
                                    Просмотреть
                                </a>
                                <button @click="editSnippet(snippet)" class="btn-primary">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                        <div class="snippet-preview">
                            <pre class="snippet-code">{{ snippet.content.substring(0, 200) }}...</pre>
                        </div>
                    </div>
                </div>

                <!-- Пустое состояние -->
                <div v-else class="empty-state">
                    <div class="empty-icon">
                        <svg class="empty-svg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="empty-title">У вас пока нет сниппетов</h3>
                    <p class="empty-description">
                        Создайте свой первый сниппет и поделитесь кодом с миром
                    </p>
                    <a href="/" class="btn-primary">Создать сниппет</a>
                </div>
            </div>

            <!-- Пагинация -->
            <div v-if="snippets.data && snippets.data.length > 0" class="pagination">
                <!-- Здесь будет пагинация -->
            </div>
        </div>

        <!-- Футер -->
        <Footer />
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { LANGUAGE_OPTIONS } from '@/types';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';

// Props от Inertia.js
interface Props {
    snippets: any;
    filters: any;
    title: string;
    description: string;
    user?: any;
}
const props = defineProps<Props>();

const searchQuery = ref(props.filters?.search || '');
const selectedPrivacy = ref(props.filters?.privacy || '');
const selectedLanguage = ref(props.filters?.language || '');
const selectedSort = ref(props.filters?.sort || 'latest');

let searchTimeout: number | null = null;

const onSearchInput = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
};

const applyFilters = () => {
    const params = new URLSearchParams();
    
    if (searchQuery.value) {
        params.append('search', searchQuery.value);
    }
    
    if (selectedPrivacy.value) {
        params.append('privacy', selectedPrivacy.value);
    }
    
    if (selectedLanguage.value) {
        params.append('language', selectedLanguage.value);
    }
    
    if (selectedSort.value) {
        params.append('sort', selectedSort.value);
    }
    
    router.visit(`/my-snippets?${params.toString()}`);
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const getPrivacyLabel = (privacy: string) => {
    const labels: Record<string, string> = {
        private: '🔒 Приватный',
        unlisted: '🔗 По ссылке',
        public: '🌐 Публичный'
    };
    return labels[privacy] || privacy;
};

const editSnippet = (snippet: any) => {
    // Здесь будет логика редактирования
    console.log('Редактирование сниппета:', snippet);
};
</script>

<style scoped>
.my-snippets-container {
    min-height: 100vh;
    background: var(--gradient-background);
    display: flex;
    flex-direction: column;
}

.my-snippets-content {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
    padding-top: 3rem;
    padding-bottom: 3rem;
    flex: 1;
}

@media (min-width: 640px) {
    .my-snippets-content {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .my-snippets-content {
        padding: 0 2rem;
    }
}

.my-snippets-header {
    text-align: center;
    margin-bottom: 3rem;
}

.my-snippets-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

@media (min-width: 768px) {
    .my-snippets-title {
        font-size: 3.75rem;
    }
}

.my-snippets-description {
    font-size: 1.25rem;
    color: var(--color-textSecondary);
    max-width: 48rem;
    margin: 0 auto;
    line-height: 1.6;
}

.filters-section {
    margin-bottom: 2rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 768px) {
    .filters-grid {
        grid-template-columns: 2fr 1fr 1fr 1fr;
    }
}

.filter-input, .filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--color-border);
    border-radius: 0.75rem;
    background-color: var(--color-surface);
    color: var(--color-text);
    font-size: 0.875rem;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.snippets-grid {
    margin-bottom: 2rem;
}

.snippets-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.snippet-card {
    background-color: var(--color-surface);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid var(--color-border);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.snippet-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.snippet-title {
    font-size: 1.125rem;
    font-weight: 600;
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
    flex-wrap: wrap;
}

.snippet-privacy {
    font-weight: 500;
}

.privacy-private {
    color: var(--color-warning);
}

.privacy-unlisted {
    color: var(--color-info);
}

.privacy-public {
    color: var(--color-success);
}

.snippet-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-secondary, .btn-primary {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-secondary {
    background-color: var(--color-surface);
    color: var(--color-text);
    border: 1px solid var(--color-border);
}

.btn-secondary:hover {
    background-color: var(--color-border);
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.snippet-preview {
    background-color: var(--color-surface);
    border-radius: 0.5rem;
    padding: 1rem;
    border: 1px solid var(--color-border);
}

.snippet-code {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    line-height: 1.5;
    color: var(--color-textSecondary);
    margin: 0;
    white-space: pre-wrap;
    overflow: hidden;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1.5rem;
    color: var(--color-textSecondary);
}

.empty-svg {
    width: 100%;
    height: 100%;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-text);
    margin-bottom: 0.5rem;
}

.empty-description {
    color: var(--color-textSecondary);
    margin-bottom: 1.5rem;
}
</style> 