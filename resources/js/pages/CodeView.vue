<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Навигация -->
        <Navigation :user="user" />
        
        <!-- Основной контент -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div v-if="!snippet" class="text-center py-20">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-6"></div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Загрузка сниппета...</h2>
                <p class="text-gray-600 dark:text-gray-400">Пожалуйста, подождите</p>
            </div>
            
            <div v-else class="space-y-8">
                <!-- Заголовок сниппета -->
                <div class="text-center">
                    <div class="inline-flex items-center space-x-3 mb-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold gradient-text">{{ getLanguageName(snippet.language) }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">Сниппет кода</p>
                        </div>
                    </div>
                </div>

                <!-- Информация о сниппете -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="card p-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Просмотры</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ snippet.access_count }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Создан</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatDate(snippet.created_at) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Тема</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ getThemeName(snippet.theme) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2" :class="snippet.is_encrypted ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-100 dark:bg-gray-900/30'">
                                <svg class="w-5 h-5" :class="snippet.is_encrypted ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="snippet.is_encrypted" fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    <path v-else d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Статус</p>
                                <p class="text-lg font-semibold" :class="snippet.is_encrypted ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white'">
                                    {{ snippet.is_encrypted ? 'Зашифрован' : 'Открытый' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Код сниппета -->
                <div class="card overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ getLanguageName(snippet.language) }}
                                </span>
                                <div class="flex space-x-1">
                                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    @click="copyCode"
                                    class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                    title="Копировать код"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                    </svg>
                                </button>
                                <button
                                    @click="copyUrl"
                                    class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                    title="Копировать ссылку"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <pre class="bg-gray-900 text-gray-100 rounded-xl p-6 overflow-x-auto text-sm font-mono leading-relaxed"><code>{{ snippet.content }}</code></pre>
                    </div>
                </div>

                <!-- Действия -->
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <button
                        @click="copyCode"
                        class="btn-primary flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                        </svg>
                        <span>Копировать код</span>
                    </button>
                    
                    <button
                        @click="copyUrl"
                        class="btn-secondary flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                        </svg>
                        <span>Поделиться</span>
                    </button>
                    
                    <router-link
                        to="/"
                        class="btn-secondary flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Создать новый</span>
                    </router-link>
                </div>

                <!-- Информация о времени жизни -->
                <div v-if="snippet.expires_at" class="text-center">
                    <div class="inline-flex items-center space-x-2 px-4 py-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded-full">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">
                            Сниппет истекает {{ formatDate(snippet.expires_at) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { CodeSnippet } from '@/types';
import { LANGUAGE_OPTIONS, THEME_OPTIONS } from '@/types';
import Navigation from '@/components/Navigation.vue';

// Props от Inertia.js
interface Props {
    hash: string;
    snippet: CodeSnippet;
    user?: any;
}
const props = defineProps<Props>();

const getLanguageName = (language: string): string => {
    return LANGUAGE_OPTIONS[language] || language;
};

const getThemeName = (theme: string): string => {
    return THEME_OPTIONS[theme] || theme;
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const copyCode = async () => {
    try {
        await navigator.clipboard.writeText(props.snippet.content);
        // Показываем уведомление об успехе
        showNotification('Код скопирован в буфер обмена', 'success');
    } catch (err) {
        console.error('Ошибка копирования:', err);
        showNotification('Ошибка копирования кода', 'error');
    }
};

const copyUrl = async () => {
    try {
        const url = window.location.href;
        await navigator.clipboard.writeText(url);
        showNotification('Ссылка скопирована в буфер обмена', 'success');
    } catch (err) {
        console.error('Ошибка копирования ссылки:', err);
        showNotification('Ошибка копирования ссылки', 'error');
    }
};

const showNotification = (message: string, type: 'success' | 'error') => {
    // Создаем уведомление
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' 
            ? 'bg-green-500 text-white' 
            : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Удаляем уведомление через 3 секунды
    setTimeout(() => {
        notification.remove();
    }, 3000);
};
</script> 