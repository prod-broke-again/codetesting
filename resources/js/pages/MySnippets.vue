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
                                    <Link :href="`/code/${snippet.hash}`" class="snippet-link">
                                        {{ snippet.content.substring(0, 50) }}...
                                    </Link>
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
                                <Link :href="`/code/${snippet.hash}`" class="btn-secondary">
                                    Просмотреть
                                </Link>
                                <button @click="openEditor(snippet)" class="btn-primary">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                        <div class="snippet-preview">
                            <pre><code class="hljs snippet-code" :class="hljsClass(snippet.language)">{{ snippet.content.substring(0, 200) }}...</code></pre>
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
                    <Link href="/" class="btn-primary">Создать сниппет</Link>
                </div>
            </div>

            <!-- Пагинация -->
            <div v-if="snippets.data && snippets.data.length > 0" class="pagination">
                <!-- Здесь будет пагинация -->
            </div>
        </div>

        <!-- Футер -->
        <Footer />

        <!-- Модальное окно редактора -->
        <div v-if="modalOpen" class="modal-backdrop" @click.self="closeEditor">
            <div class="modal-card">
                <div class="modal-header">
                    <div class="modal-title">Редактирование сниппета</div>
                    <button class="modal-close" @click="closeEditor">✕</button>
                </div>
                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Язык</label>
                            <select v-model="edit.language" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-2 text-sm">
                                <option v-for="(label, value) in LANGUAGE_OPTIONS" :key="value" :value="value">{{ label }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Тема</label>
                            <select v-model="edit.theme" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-2 text-sm">
                                <option value="vs-dark">VS Code Dark</option>
                                <option value="vs-light">VS Code Light</option>
                            </select>
                        </div>
                    </div>
                    <div ref="editorContainer" class="h-96 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" @click="closeEditor">Отмена</button>
                    <button class="btn-primary" :disabled="saving" @click="saveEdit">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUpdated, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import { LANGUAGE_OPTIONS } from '@/types';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import { Link } from '@inertiajs/vue3';
import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark.css';
import * as monaco from 'monaco-editor';

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

const hljsClass = (lang: string) => {
    const map: Record<string, string> = {
        php: 'php', javascript: 'javascript', typescript: 'typescript', python: 'python', java: 'java', cpp: 'cpp', csharp: 'csharp', html: 'xml', css: 'css', sql: 'sql', bash: 'bash', json: 'json', xml: 'xml', markdown: 'markdown', vue: 'vue', jsx: 'javascript', tsx: 'typescript', blade: 'php', 'php-html': 'php', 'php-blade': 'php', 'html-css': 'xml', 'html-js': 'xml'
    };
    const key = (lang || '').toString().toLowerCase();
    return map[key] ? `language-${map[key]}` : '';
};

const highlight = () => {
    document.querySelectorAll('code.hljs').forEach((el) => hljs.highlightElement(el as HTMLElement));
};

onMounted(highlight);
onUpdated(highlight);

// ===== Редактор (модалка) =====
const modalOpen = ref(false);
const editorContainer = ref<HTMLElement | null>(null);
let editor: monaco.editor.IStandaloneCodeEditor | null = null;
const editingSnippet = ref<any>(null);
const edit = ref<{ content: string; language: string; theme: string }>({ content: '', language: 'php', theme: 'vs-dark' });
const saving = ref(false);

const toMonacoLanguage = (lang: string) => {
    const map: Record<string, string> = {
        php: 'php', javascript: 'javascript', typescript: 'typescript', python: 'python', java: 'java', cpp: 'cpp', csharp: 'csharp', html: 'html', css: 'css', sql: 'sql', bash: 'shell', json: 'json', xml: 'xml', markdown: 'markdown', vue: 'html', jsx: 'javascript', tsx: 'typescript', blade: 'php', 'php-html': 'php', 'php-blade': 'php', 'html-css': 'html', 'html-js': 'html'
    };
    const key = (lang || '').toString().toLowerCase();
    return map[key] || 'plaintext';
};

const toMonacoTheme = (theme: string) => (theme === 'vs-light' ? 'vs' : 'vs-dark');

const openEditor = async (snippet: any) => {
    editingSnippet.value = snippet;
    edit.value = {
        content: snippet.content,
        language: snippet.language,
        theme: snippet.theme === 'vs-light' ? 'vs-light' : 'vs-dark'
    };
    modalOpen.value = true;
    await nextTick();
    editor = monaco.editor.create(editorContainer.value as HTMLElement, {
        value: edit.value.content,
        language: toMonacoLanguage(edit.value.language),
        theme: toMonacoTheme(edit.value.theme),
        automaticLayout: true,
        minimap: { enabled: false },
        fontSize: 14,
        lineNumbers: 'on',
        scrollBeyondLastLine: false,
    });
};

const closeEditor = () => {
    if (editor) {
        editor.dispose();
        editor = null;
    }
    modalOpen.value = false;
};

const saveEdit = async () => {
    if (!editor || !editingSnippet.value) return;
    try {
        saving.value = true;
        const payload: Record<string, any> = {
            content: editor.getValue(),
            language: edit.value.language,
            theme: edit.value.theme,
        };
        const response = await fetch(`/api/snippets/${editingSnippet.value.hash}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        });
        if (!response.ok) {
            const text = await response.text();
            console.error('Save error:', text);
            alert('Не удалось сохранить сниппет');
            return;
        }
        // Обновим локально превью
        editingSnippet.value.content = payload.content;
        closeEditor();
    } catch (e) {
        console.error(e);
        alert('Ошибка сохранения');
    } finally {
        saving.value = false;
    }
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

.privacy-private { color: var(--color-warning); }
.privacy-unlisted { color: var(--color-info); }
.privacy-public { color: var(--color-success); }

.snippet-actions { display: flex; gap: 0.5rem; }

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

.btn-secondary:hover { background-color: var(--color-border); }

.btn-primary { background: var(--gradient-primary); color: white; }
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

.snippet-preview { background-color: var(--color-surface); border-radius: 0.5rem; border: 1px solid var(--color-border); overflow: hidden; }

/* Стили теперь применяются к code.snippet-code, а не к pre */
code.snippet-code {
    display: block;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    line-height: 1.5;
    color: var(--color-textSecondary);
    white-space: pre-wrap;
    overflow: hidden;
}

/* Модалка */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
}
.modal-card {
    width: 100%;
    max-width: 960px;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 0.75rem;
    overflow: hidden;
}
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--color-border); }
.modal-title { font-weight: 600; }
.modal-close { background: transparent; border: none; font-size: 1.25rem; cursor: pointer; }
.modal-body { padding: 1rem; }
.modal-footer { padding: 0.75rem 1rem; display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid var(--color-border); }
</style> 