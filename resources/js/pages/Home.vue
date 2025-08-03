<template>
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Делитесь кодом безопасно
            </h1>
            <p class="text-xl text-gray-600">
                Создавайте зашифрованные сниппеты кода и делитесь ими с коллегами
            </p>
        </div>

        <!-- Форма создания сниппета -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-4">Создать новый сниппет</h2>
            
            <form @submit.prevent="createSnippet" class="space-y-4">
                <!-- Выбор языка -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Язык программирования
                    </label>
                    <div class="flex items-center space-x-2 mb-2">
                        <button 
                            type="button"
                            @click="autoDetectLanguage"
                            class="text-sm text-blue-600 hover:text-blue-800 underline"
                        >
                            🔍 Автоопределение
                        </button>
                        <span v-if="detectionConfidence > 0" class="text-sm text-gray-500">
                            Уверенность: {{ detectionConfidence }}%
                        </span>
                    </div>
                    <select 
                        v-model="form.language"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <optgroup label="Основные языки">
                            <option v-for="(name, lang) in basicLanguages" :key="lang" :value="lang">
                                {{ name }}
                            </option>
                        </optgroup>
                        <optgroup label="Смешанные типы">
                            <option v-for="(name, lang) in mixedLanguages" :key="lang" :value="lang">
                                {{ name }}
                            </option>
                        </optgroup>
                        <optgroup label="Дополнительные языки">
                            <option v-for="(name, lang) in additionalLanguages" :key="lang" :value="lang">
                                {{ name }}
                            </option>
                        </optgroup>
                    </select>
                    <!-- Альтернативные языки -->
                    <div v-if="alternativeLanguages.length > 0" class="mt-2">
                        <p class="text-xs text-gray-500 mb-1">Возможные альтернативы:</p>
                        <div class="flex flex-wrap gap-1">
                            <button 
                                v-for="lang in alternativeLanguages" 
                                :key="lang"
                                type="button"
                                @click="form.language = lang"
                                class="text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded"
                            >
                                {{ LANGUAGE_OPTIONS[lang] }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Выбор темы -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Тема оформления
                    </label>
                    <select 
                        v-model="form.theme"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option v-for="(name, theme) in themeOptions" :key="theme" :value="theme">
                            {{ name }}
                        </option>
                    </select>
                </div>

                <!-- Редактор кода -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Код
                    </label>
                    <textarea 
                        v-model="form.content"
                        @input="onCodeInput"
                        rows="12"
                        placeholder="Введите ваш код здесь..."
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                    ></textarea>
                </div>

                <!-- Кнопка создания -->
                <div class="flex justify-end">
                    <button 
                        type="submit"
                        :disabled="isLoading"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ isLoading ? 'Создание...' : 'Создать сниппет' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import type { CreateSnippetForm, ProgrammingLanguage, CodeTheme } from '@/types';
import { LANGUAGE_OPTIONS, THEME_OPTIONS } from '@/types';
import { detectLanguage, getDetectionConfidence, getAlternativeLanguages } from '@/utils/languageDetector';

const router = useRouter();
const isLoading = ref<boolean>(false);
const detectionConfidence = ref<number>(0);
const alternativeLanguages = ref<ProgrammingLanguage[]>([]);

const form = reactive<CreateSnippetForm>({
    content: '',
    language: 'php' as ProgrammingLanguage,
    theme: 'vs-dark' as CodeTheme
});

// Группировка языков для лучшего UX
const basicLanguages = computed(() => {
    const basic = ['php', 'javascript', 'python', 'java', 'csharp', 'cpp', 'go', 'rust', 'typescript', 'html', 'css', 'sql'];
    return Object.fromEntries(
        Object.entries(LANGUAGE_OPTIONS).filter(([key]) => basic.includes(key))
    );
});

const mixedLanguages = computed(() => {
    const mixed = ['php-html', 'vue', 'blade', 'jsx', 'tsx', 'html-css', 'html-js', 'php-blade'];
    return Object.fromEntries(
        Object.entries(LANGUAGE_OPTIONS).filter(([key]) => mixed.includes(key))
    );
});

const additionalLanguages = computed(() => {
    const additional = ['ruby', 'swift', 'kotlin', 'scala', 'dart', 'elixir', 'haskell', 'clojure', 'bash', 'powershell', 'yaml', 'json', 'xml', 'markdown'];
    return Object.fromEntries(
        Object.entries(LANGUAGE_OPTIONS).filter(([key]) => additional.includes(key))
    );
});

const themeOptions = computed(() => THEME_OPTIONS);

// Автоматическое определение языка при вводе кода
let detectionTimeout: NodeJS.Timeout | null = null;

const onCodeInput = () => {
    // Очищаем предыдущий таймаут
    if (detectionTimeout) {
        clearTimeout(detectionTimeout);
    }
    
    // Запускаем определение через 1 секунду после остановки ввода
    detectionTimeout = setTimeout(() => {
        if (form.content.trim()) {
            autoDetectLanguage();
        }
    }, 1000);
};

const autoDetectLanguage = () => {
    if (!form.content.trim()) {
        alert('Введите код для автоопределения языка');
        return;
    }
    
    const detectedLanguage = detectLanguage(form.content);
    const confidence = getDetectionConfidence(form.content, detectedLanguage);
    const alternatives = getAlternativeLanguages(form.content, detectedLanguage);
    
    // Обновляем язык независимо от уверенности при ручном нажатии кнопки
    form.language = detectedLanguage;
    
    detectionConfidence.value = confidence;
    alternativeLanguages.value = alternatives;
    
    console.log('Автоопределение:', {
        detectedLanguage,
        confidence,
        alternatives,
        content: form.content.substring(0, 100) + '...'
    });
};

// Следим за изменениями контента для автоматического определения
watch(() => form.content, (newContent) => {
    if (newContent.trim() && newContent.length > 10) {
        // Автоматически определяем язык при достаточном количестве кода
        const detectedLanguage = detectLanguage(newContent);
        const confidence = getDetectionConfidence(newContent, detectedLanguage);
        const alternatives = getAlternativeLanguages(newContent, detectedLanguage);
        
        // Обновляем язык только если уверенность выше 60%
        if (confidence > 60) {
            form.language = detectedLanguage;
        }
        
        detectionConfidence.value = confidence;
        alternativeLanguages.value = alternatives;
    }
}, { deep: true });

const createSnippet = async (): Promise<void> => {
    if (!form.content.trim()) {
        alert('Введите код');
        return;
    }

    isLoading.value = true;
    
    try {
        const response = await fetch('/api/codes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(form)
        });

        if (response.ok) {
            const data = await response.json();
            router.push(`/code/${data.hash}`);
        } else {
            throw new Error('Ошибка создания сниппета');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Ошибка создания сниппета');
    } finally {
        isLoading.value = false;
    }
};
</script> 