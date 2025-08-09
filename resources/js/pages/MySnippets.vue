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
                        <TextInput :model-value="searchQuery" @update:modelValue="(v:string)=>{searchQuery=v; onSearchInput();}" placeholder="Поиск сниппетов..." />
                    </div>

                    <!-- Приватность -->
                    <div class="filter-item">
                        <SelectInput :model-value="selectedPrivacy" @update:modelValue="(v:string)=>{selectedPrivacy=v; applyFilters();}" :options="privacyOptions" placeholder="Все типы" />
                    </div>

                    <!-- Язык -->
                    <div class="filter-item">
                        <SelectInput :model-value="selectedLanguage" @update:modelValue="(v:string)=>{selectedLanguage=v; applyFilters();}" :options="languageOptions" placeholder="Все языки" />
                    </div>

                    <!-- Сортировка -->
                    <div class="filter-item">
                        <SelectInput :model-value="selectedSort" @update:modelValue="(v:string)=>{selectedSort=v; applyFilters();}" :options="sortOptions" />
                    </div>
                </div>
            </div>

            <!-- Список сниппетов -->
            <div class="snippets-grid">
                <div v-if="snippets.data && snippets.data.length > 0" class="snippets-list">
                    <SnippetCard v-for="snippet in snippets.data" :key="snippet.id" :snippet="snippet">
                        <template #meta>
                            <span class="snippet-language">{{ LANGUAGE_OPTIONS[snippet.language as keyof typeof LANGUAGE_OPTIONS] || snippet.language }}</span>
                            <span class="snippet-privacy" :class="`privacy-${snippet.privacy}`">{{ getPrivacyLabel(snippet.privacy) }}</span>
                            <span class="snippet-date">{{ formatDate(snippet.created_at) }}</span>
                            <span class="snippet-views">{{ snippet.access_count }} просмотров</span>
                        </template>
                        <template #actions>
                            <Link :href="`/code/${snippet.hash}`">
                                <ButtonSecondary>Просмотреть</ButtonSecondary>
                            </Link>
                            <ButtonPrimary @click="openEditor(snippet)">Редактировать</ButtonPrimary>
                        </template>
                    </SnippetCard>
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
                    <Link href="/"><ButtonPrimary>Создать сниппет</ButtonPrimary></Link>
                </div>
            </div>

            <!-- Пагинация -->
            <div v-if="snippets.data && snippets.data.length > 0" class="pagination">
                <!-- Здесь будет пагинация -->
            </div>
        </div>

        <!-- Футер -->
        <Footer />

        <CodeEditorModal
            :open="modalOpen"
            :value="edit.content"
            :language="edit.language"
            :theme="edit.theme"
            :language-options="LANGUAGE_OPTIONS"
            :saving="saving"
            @update:value="(v:string)=> edit.content = v"
            @update:language="(v:string)=> edit.language = v"
            @update:theme="(v:string)=> edit.theme = v"
            @close="closeEditor"
            @save="saveEdit"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUpdated, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { LANGUAGE_OPTIONS } from '@/types';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark.css';
import CodeEditorModal from '@/components/modals/CodeEditorModal.vue';
import SnippetCard from '@/components/snippets/SnippetCard.vue';
import { updateSnippet as updateSnippetApi, type UpdateSnippetPayload } from '@/services/snippetService';
import { useToast } from '@/composables/useToast';
import ButtonPrimary from '@/components/buttons/ButtonPrimary.vue';
import ButtonSecondary from '@/components/buttons/ButtonSecondary.vue';
import TextInput from '@/components/inputs/TextInput.vue';
import SelectInput from '@/components/inputs/SelectInput.vue';

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

const privacyOptions = [
  { value: '', label: 'Все типы' },
  { value: 'private', label: '🔒 Приватные' },
  { value: 'unlisted', label: '🔗 По ссылке' },
  { value: 'public', label: '🌐 Публичные' },
];
const languageOptions = computed(() => Object.entries(LANGUAGE_OPTIONS).map(([value, label]) => ({ value, label })));
const sortOptions = [
  { value: 'latest', label: 'Сначала новые' },
  { value: 'popular', label: 'По популярности' },
  { value: 'oldest', label: 'Сначала старые' },
];

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

const highlight = () => {
    document.querySelectorAll('code.hljs').forEach((el) => hljs.highlightElement(el as HTMLElement));
};

onMounted(highlight);
onUpdated(highlight);

// ===== Редактор (модалка) =====
const modalOpen = ref(false);
const editingSnippet = ref<any>(null);
const edit = ref<{ content: string; language: string; theme: string }>({ content: '', language: 'php', theme: 'vs-dark' });
const saving = ref(false);
const { show: toast } = useToast();

const openEditor = async (snippet: any) => {
    editingSnippet.value = snippet;
    edit.value = {
        content: snippet.content,
        language: snippet.language,
        theme: snippet.theme === 'vs-light' ? 'vs-light' : 'vs-dark'
    };
    modalOpen.value = true;
};

const closeEditor = () => {
    modalOpen.value = false;
};

const saveEdit = async () => {
    if (!editingSnippet.value) return;
    try {
        saving.value = true;
        const payload: UpdateSnippetPayload = {
            content: edit.value.content,
            language: edit.value.language,
            theme: edit.value.theme,
        };
        await updateSnippetApi(editingSnippet.value.hash, payload as UpdateSnippetPayload);
        // Обновим локально превью
        editingSnippet.value.content = payload.content;
        toast('Сниппет сохранён', 'success');
        closeEditor();
    } catch (e) {
        console.error(e);
        toast('Ошибка сохранения', 'error');
    } finally {
        saving.value = false;
    }
};
</script>

<style scoped>
.my-snippets-container { min-height: 100vh; background: var(--gradient-background); display: flex; flex-direction: column; }
.my-snippets-content { max-width: 80rem; margin: 0 auto; padding: 0 1rem; padding-top: 3rem; padding-bottom: 3rem; flex: 1; }
@media (min-width: 640px) { .my-snippets-content { padding: 0 1.5rem; } }
@media (min-width: 1024px) { .my-snippets-content { padding: 0 2rem; } }
.my-snippets-header { text-align: center; margin-bottom: 3rem; }
.my-snippets-title { font-size: 3rem; font-weight: 700; margin-bottom: 1.5rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
@media (min-width: 768px) { .my-snippets-title { font-size: 3.75rem; } }
.my-snippets-description { font-size: 1.25rem; color: var(--color-textSecondary); max-width: 48rem; margin: 0 auto; line-height: 1.6; }

.filters-section { margin-bottom: 2rem; }
.filters-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 768px) { .filters-grid { grid-template-columns: 2fr 1fr 1fr 1fr; } }
/* Поля фильтров теперь общими компонентами */

.snippets-grid { margin-bottom: 2rem; }
.snippets-list { display: flex; flex-direction: column; gap: 1.5rem; }

.snippet-card { background-color: var(--color-surface); backdrop-filter: blur(12px); border-radius: 1rem; padding: 1.5rem; border: 1px solid var(--color-border); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
.snippet-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
.snippet-title { font-size: 1.125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.5rem; }
.snippet-link { color: var(--color-primary); text-decoration: none; }
.snippet-link:hover { text-decoration: underline; }
.snippet-meta { display: flex; gap: 1rem; font-size: 0.875rem; color: var(--color-textSecondary); flex-wrap: wrap; }
.snippet-privacy { font-weight: 500; }
.privacy-private { color: var(--color-warning); }
.privacy-unlisted { color: var(--color-info); }
.privacy-public { color: var(--color-success); }
.snippet-actions { display: flex; gap: 0.5rem; }

/* Глобальный .snippet-preview используется */

/* Просмотр кода */
code.snippet-code { display: block; font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; font-size: 0.875rem; line-height: 1.5; color: var(--color-textSecondary); white-space: pre-wrap; overflow: hidden; }
</style> 