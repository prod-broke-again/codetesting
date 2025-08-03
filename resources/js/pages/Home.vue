<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Навигация -->
        <Navigation :user="user" />
        
        <!-- Основной контент -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Заголовок -->
            <div class="text-center mb-12">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    {{ title }}
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    {{ description }}
                </p>
            </div>

            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Сниппетов создано</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">1,234</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Языков поддержки</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">30+</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Активных пользователей</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">567</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Форма создания сниппета -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <!-- Заголовок формы -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Создать новый сниппет</h2>
                                <p class="text-blue-100">Быстро и безопасно поделитесь своим кодом</p>
                            </div>
                        </div>
                    </div>

                    <!-- Содержимое формы -->
                    <div class="p-8">
                        <form @submit.prevent="createSnippet" class="space-y-6">
                            <!-- Поле для кода -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Ваш код
                                </label>
                                <div class="relative">
                                    <textarea
                                        id="code"
                                        v-model="form.content"
                                        @input="onCodeInput"
                                        rows="12"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none font-mono text-sm"
                                        placeholder="Вставьте ваш код здесь..."
                                        required
                                    ></textarea>
                                    
                                    <!-- Индикатор определения языка -->
                                    <div v-if="detectionConfidence > 0" class="absolute top-3 right-3 flex items-center space-x-2">
                                        <div class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full text-xs font-medium">
                                            {{ detectionConfidence }}% {{ detectedLanguage }}
                                        </div>
                                        <button
                                            type="button"
                                            @click="autoDetectLanguage"
                                            class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors"
                                            title="Автоопределение языка"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Настройки -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Язык программирования -->
                                <div>
                                    <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Язык программирования
                                    </label>
                                    <select
                                        id="language"
                                        v-model="form.language"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option v-for="(label, value) in LANGUAGE_OPTIONS" :key="value" :value="value">
                                            {{ label }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Тема оформления -->
                                <div>
                                    <label for="theme" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Тема оформления
                                    </label>
                                    <select
                                        id="theme"
                                        v-model="form.theme"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option v-for="(label, value) in THEME_OPTIONS" :key="value" :value="value">
                                            {{ label }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Время истечения -->
                                <div>
                                    <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Время жизни
                                    </label>
                                    <select
                                        id="expires_at"
                                        v-model="form.expires_at"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option value="1">1 день</option>
                                        <option value="7">7 дней</option>
                                        <option value="30">30 дней</option>
                                        <option value="0">Навсегда</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Дополнительные опции -->
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input
                                        v-model="form.is_encrypted"
                                        type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Зашифровать сниппет</span>
                                </label>
                            </div>

                            <!-- Кнопка создания -->
                            <div class="flex justify-center">
                                <button
                                    type="submit"
                                    :disabled="isLoading"
                                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                >
                                    <div v-if="isLoading" class="flex items-center space-x-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Создание...</span>
                                    </div>
                                    <div v-else class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Создать сниппет</span>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Особенности -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Безопасность</h3>
                    <p class="text-gray-600 dark:text-gray-400">Ваш код защищен шифрованием и временными токенами доступа</p>
                </div>
                
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">30+ языков</h3>
                    <p class="text-gray-600 dark:text-gray-400">Поддержка всех популярных языков программирования</p>
                </div>
                
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Умное восстановление</h3>
                    <p class="text-gray-600 dark:text-gray-400">Автоматическое связывание сниппетов по fingerprint</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import type { CreateSnippetForm, ProgrammingLanguage, CodeTheme } from '@/types';
import { LANGUAGE_OPTIONS, THEME_OPTIONS } from '@/types';
import { detectLanguage, getDetectionConfidence, getAlternativeLanguages } from '@/utils/languageDetector';
import Navigation from '@/components/Navigation.vue';

// Props от Inertia.js
interface Props {
    title: string;
    description: string;
    user?: any;
}
const props = defineProps<Props>();

const isLoading = ref<boolean>(false);
let detectionTimeout: number | null = null;

const form = reactive<CreateSnippetForm>({
    content: '',
    language: 'php' as ProgrammingLanguage,
    theme: 'vs-dark' as CodeTheme,
    is_encrypted: false,
    expires_at: '1'
});

const detectedLanguage = ref<string>('');
const detectionConfidence = ref<number>(0);
const alternativeLanguages = ref<string[]>([]);

const onCodeInput = () => {
    if (detectionTimeout) {
        clearTimeout(detectionTimeout);
    }
    
    detectionTimeout = setTimeout(() => {
        const result = detectLanguage(form.content);
        detectedLanguage.value = result;
        detectionConfidence.value = getDetectionConfidence(form.content, result);
        alternativeLanguages.value = getAlternativeLanguages(form.content);
        
        if (detectionConfidence.value > 60) {
            form.language = result as ProgrammingLanguage;
        }
    }, 500);
};

const autoDetectLanguage = () => {
    const result = detectLanguage(form.content);
    detectedLanguage.value = result;
    detectionConfidence.value = getDetectionConfidence(form.content, result);
    form.language = result as ProgrammingLanguage;
};

const createSnippet = async (): Promise<void> => {
    isLoading.value = true;
    
    try {
        const response = await fetch('/api/codes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Fingerprint': localStorage.getItem('fingerprint') || ''
            },
            body: JSON.stringify(form)
        });

        if (response.ok) {
            const data = await response.json();
            router.visit(`/code/${data.data.hash}`);
        } else {
            const errorData = await response.json();
            alert(errorData.message || 'Ошибка создания сниппета');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Ошибка соединения с сервером');
    } finally {
        isLoading.value = false;
    }
};

// Следим за изменениями контента для автоопределения языка
watch(() => form.content, () => {
    onCodeInput();
});
</script> 