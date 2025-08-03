<template>
    <div class="max-w-4xl mx-auto">
        <div v-if="!snippet" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Загрузка сниппета...</p>
        </div>

        <div v-else class="bg-white rounded-lg shadow-md">
            <!-- Заголовок -->
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Сниппет кода</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Язык: {{ getLanguageName(snippet.language) }} | 
                            Тема: {{ getThemeName(snippet.theme) }} |
                            Просмотров: {{ snippet.access_count }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <button 
                            @click="copyCode"
                            class="bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-200"
                        >
                            📋 Копировать код
                        </button>
                        <button 
                            @click="copyUrl"
                            class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200"
                        >
                            🔗 Копировать ссылку
                        </button>
                    </div>
                </div>
            </div>

            <!-- Код -->
            <div class="p-6">
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{{ snippet.content }}</code></pre>
            </div>

            <!-- Информация -->
            <div class="border-t border-gray-200 px-6 py-4">
                <div class="text-sm text-gray-500">
                    <p>Создан: {{ formatDate(snippet.created_at) }}</p>
                    <p v-if="snippet.expires_at">Истекает: {{ formatDate(snippet.expires_at) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { CodeSnippet } from '@/types';
import { LANGUAGE_OPTIONS, THEME_OPTIONS } from '@/types';

// Props от Inertia.js
interface Props {
    hash: string;
    snippet: CodeSnippet;
}

const props = defineProps<Props>();

const getLanguageName = (language: string): string => {
    return LANGUAGE_OPTIONS[language as keyof typeof LANGUAGE_OPTIONS] || language;
};

const getThemeName = (theme: string): string => {
    return THEME_OPTIONS[theme as keyof typeof THEME_OPTIONS] || theme;
};

const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleString('ru-RU');
};

const copyCode = async () => {
    try {
        await navigator.clipboard.writeText(props.snippet.content);
        alert('Код скопирован в буфер обмена');
    } catch (err) {
        console.error('Ошибка копирования:', err);
    }
};

const copyUrl = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        alert('Ссылка скопирована в буфер обмена');
    } catch (err) {
        console.error('Ошибка копирования:', err);
    }
};
</script> 