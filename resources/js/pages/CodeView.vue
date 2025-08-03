<template>
    <div class="max-w-6xl mx-auto">
        <div v-if="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Загрузка сниппета...</p>
        </div>

        <div v-else-if="error" class="text-center py-8">
            <div class="text-red-600 text-xl mb-4">{{ error }}</div>
            <router-link to="/" class="text-blue-600 hover:underline">
                Вернуться на главную
            </router-link>
        </div>

        <div v-else-if="snippet" class="space-y-6">
            <!-- Заголовок -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Сниппет кода
                        </h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span>Язык: {{ getLanguageName(snippet.language) }}</span>
                            <span>Тема: {{ getThemeName(snippet.theme) }}</span>
                            <span>Просмотров: {{ snippet.access_count }}</span>
                            <span v-if="snippet.expires_at">
                                Истекает: {{ formatDate(snippet.expires_at) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button 
                            @click="copyCode"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                        >
                            Копировать код
                        </button>
                        <button 
                            @click="copyUrl"
                            class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700"
                        >
                            Копировать ссылку
                        </button>
                    </div>
                </div>
            </div>

            <!-- Редактор кода -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-800 px-4 py-2 flex justify-between items-center">
                    <span class="text-white font-mono text-sm">{{ getLanguageName(snippet.language) }}</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-400 text-xs">{{ snippet.language }}</span>
                    </div>
                </div>
                <div class="p-4">
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-md overflow-x-auto"><code>{{ snippet.content }}</code></pre>
                </div>
            </div>

            <!-- Информация о сниппете -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Информация о сниппете</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Создан:</span>
                        <span class="ml-2">{{ formatDate(snippet.created_at) }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Хеш:</span>
                        <span class="ml-2 font-mono">{{ snippet.hash }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Шифрование:</span>
                        <span class="ml-2">{{ snippet.is_encrypted ? 'Включено' : 'Отключено' }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Тип:</span>
                        <span class="ml-2">{{ snippet.is_guest ? 'Гостевой' : 'Зарегистрированный' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import type { CodeSnippet } from '@/types';
import { LANGUAGE_OPTIONS, THEME_OPTIONS } from '@/types';

const route = useRoute();
const loading = ref<boolean>(true);
const error = ref<string>('');
const snippet = ref<CodeSnippet | null>(null);

const fetchSnippet = async (): Promise<void> => {
    try {
        const response = await fetch(`/api/codes/${route.params.hash}`);
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Сниппет не найден');
        }

        const data = await response.json();
        snippet.value = data.data;
    } catch (err) {
        error.value = err instanceof Error ? err.message : 'Ошибка загрузки сниппета';
    } finally {
        loading.value = false;
    }
};

const copyCode = async (): Promise<void> => {
    if (snippet.value) {
        try {
            await navigator.clipboard.writeText(snippet.value.content);
            alert('Код скопирован в буфер обмена');
        } catch (err) {
            console.error('Ошибка копирования:', err);
        }
    }
};

const copyUrl = async (): Promise<void> => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        alert('Ссылка скопирована в буфер обмена');
    } catch (err) {
        console.error('Ошибка копирования:', err);
    }
};

const getLanguageName = (language: string): string => {
    return LANGUAGE_OPTIONS[language as keyof typeof LANGUAGE_OPTIONS] || language;
};

const getThemeName = (theme: string): string => {
    return THEME_OPTIONS[theme as keyof typeof THEME_OPTIONS] || theme;
};

const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleString('ru-RU');
};

onMounted(() => {
    fetchSnippet();
});
</script> 