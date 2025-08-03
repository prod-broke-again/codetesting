<template>
    <div class="home-container">
        <!-- Навигация -->
        <Navigation :user="user" />
        
        <!-- Основной контент -->
        <div class="home-content">
            <!-- Заголовок -->
            <div class="home-header">
                <h1 class="home-title">
                    {{ title }}
                </h1>
                <p class="home-description">
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
                            <p class="stat-label">Сниппетов создано</p>
                            <p class="stat-value">1,234</p>
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
                            <p class="stat-label">Языков поддержки</p>
                            <p class="stat-value">30+</p>
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
                            <p class="stat-label">Активных пользователей</p>
                            <p class="stat-value">567</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Форма создания сниппета -->
            <div class="form-container">
                <div class="form-card">
                    <!-- Заголовок формы -->
                    <div class="form-header">
                        <div class="form-header-content">
                            <div class="form-header-icon">
                                <svg class="form-header-svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="form-header-title">Создать новый сниппет</h2>
                                <p class="form-header-subtitle">Быстро и безопасно поделитесь своим кодом</p>
                            </div>
                        </div>
                    </div>

                    <!-- Содержимое формы -->
                    <div class="form-body">
                        <form @submit.prevent="createSnippet" class="form">
                            <!-- Поле для кода -->
                            <div class="form-field">
                                <label for="code" class="form-label">
                                    Ваш код
                                </label>
                                <div class="form-input-container">
                                    <textarea
                                        id="code"
                                        v-model="form.content"
                                        @input="onCodeInput"
                                        rows="12"
                                        class="form-textarea"
                                        placeholder="Вставьте ваш код здесь..."
                                        required
                                    ></textarea>
                                    
                                    <!-- Индикатор определения языка -->
                                    <div class="language-indicator">
                                        <div class="language-info">
                                            <span class="language-label">Определенный язык:</span>
                                            <span class="language-value">{{ detectedLanguage }}</span>
                                        </div>
                                        <div class="confidence-info">
                                            <span class="confidence-label">Уверенность:</span>
                                            <span class="confidence-value">{{ confidence }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Настройки сниппета -->
                            <div class="form-settings">
                                <div class="form-row">
                                    <!-- Выбор языка -->
                                    <div class="form-field-half">
                                        <label for="language" class="form-label">Язык программирования</label>
                                        <select
                                            id="language"
                                            v-model="form.language"
                                            class="form-select"
                                            required
                                        >
                                            <option value="">Автоопределение</option>
                                            <option v-for="(label, value) in LANGUAGE_OPTIONS" :key="value" :value="value">
                                                {{ label }}
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Выбор темы -->
                                    <div class="form-field-half">
                                        <label for="theme" class="form-label">Тема подсветки</label>
                                        <select
                                            id="theme"
                                            v-model="form.theme"
                                            class="form-select"
                                            required
                                        >
                                            <option v-for="(label, value) in THEME_OPTIONS" :key="value" :value="value">
                                                {{ label }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- Шифрование -->
                                    <div class="form-field-half">
                                        <label class="form-checkbox-label">
                                            <input
                                                type="checkbox"
                                                v-model="form.is_encrypted"
                                                class="form-checkbox"
                                            />
                                            <span class="form-checkbox-text">Зашифровать сниппет</span>
                                        </label>
                                    </div>

                                    <!-- Время жизни -->
                                    <div class="form-field-half">
                                        <label for="expires_at" class="form-label">Время жизни</label>
                                        <select
                                            id="expires_at"
                                            v-model="form.expires_at"
                                            class="form-select"
                                        >
                                            <option value="">Без ограничений</option>
                                            <option value="1h">1 час</option>
                                            <option value="24h">24 часа</option>
                                            <option value="7d">7 дней</option>
                                            <option value="30d">30 дней</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопка создания -->
                            <div class="form-actions">
                                <button type="submit" class="form-submit" :disabled="isLoading">
                                    <span v-if="isLoading">Создание...</span>
                                    <span v-else>Создать сниппет</span>
                                </button>
                            </div>
                        </form>
                    </div>
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
const confidence = ref<number>(0);
const alternativeLanguages = ref<string[]>([]);

const onCodeInput = () => {
    if (detectionTimeout) {
        clearTimeout(detectionTimeout);
    }
    
    detectionTimeout = setTimeout(() => {
        const result = detectLanguage(form.content);
        detectedLanguage.value = result;
        const confidenceValue = getDetectionConfidence(form.content, result);
        detectionConfidence.value = confidenceValue;
        confidence.value = confidenceValue;
        alternativeLanguages.value = getAlternativeLanguages(form.content);
        
        if (confidenceValue > 60) {
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


</script> 

<style scoped>
.home-container {
    min-height: 100vh;
    background: var(--gradient-background);
}

.home-content {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
    padding-top: 3rem;
    padding-bottom: 3rem;
}

@media (min-width: 640px) {
    .home-content {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .home-content {
        padding: 0 2rem;
    }
}

.home-header {
    text-align: center;
    margin-bottom: 3rem;
}

.home-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

@media (min-width: 768px) {
    .home-title {
        font-size: 3.75rem;
    }
}

.home-description {
    font-size: 1.25rem;
    color: var(--color-textSecondary);
    max-width: 48rem;
    margin: 0 auto;
    line-height: 1.6;
}

@media (min-width: 768px) {
    .home-description {
        font-size: 1.5rem;
    }
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 3rem;
}

@media (min-width: 768px) {
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

.form-container {
    max-width: 64rem;
    margin: 0 auto;
}

.form-card {
    background-color: var(--color-surface);
    backdrop-filter: blur(12px);
    border-radius: 1.5rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border: 1px solid var(--color-border);
    overflow: hidden;
}

.form-header {
    background: var(--gradient-primary);
    padding: 2rem;
}

.form-header-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-header-icon {
    padding: 0.5rem;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 0.5rem;
}

.form-header-svg {
    width: 1.5rem;
    height: 1.5rem;
    color: white;
}

.form-header-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
}

.form-header-subtitle {
    color: rgba(255, 255, 255, 0.8);
}

.form-body {
    padding: 2rem;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text);
}

.form-input-container {
    position: relative;
}

.form-textarea {
    display: block;
    width: 100%;
    padding: 1rem;
    border: 1px solid var(--color-border);
    border-radius: 0.75rem;
    background-color: var(--color-surface);
    color: var(--color-text);
    resize: none;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    line-height: 1.5;
}

.form-textarea:focus {
    outline: none;
    ring: 2px;
    ring-color: var(--color-primary);
    border-color: transparent;
}

.form-textarea::placeholder {
    color: var(--color-textSecondary);
}

.language-indicator {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    background-color: var(--color-surface);
    padding: 0.5rem;
    border-radius: 0.5rem;
    border: 1px solid var(--color-border);
    font-size: 0.75rem;
}

.language-info, .confidence-info {
    display: flex;
    gap: 0.5rem;
}

.language-label, .confidence-label {
    color: var(--color-textSecondary);
}

.language-value, .confidence-value {
    color: var(--color-primary);
    font-weight: 500;
}

.form-settings {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .form-row {
        grid-template-columns: 1fr 1fr;
    }
}

.form-field-half {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-select {
    display: block;
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--color-border);
    border-radius: 0.75rem;
    background-color: var(--color-surface);
    color: var(--color-text);
    font-size: 0.875rem;
}

.form-select:focus {
    outline: none;
    ring: 2px;
    ring-color: var(--color-primary);
    border-color: transparent;
}

.form-checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    color: var(--color-primary);
    background-color: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 0.25rem;
}

.form-checkbox-text {
    font-size: 0.875rem;
    color: var(--color-text);
}

.form-actions {
    display: flex;
    justify-content: center;
}

.form-submit {
    padding: 1rem 2rem;
    background: var(--gradient-primary);
    color: white;
    font-weight: 600;
    border-radius: 0.75rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.form-submit:hover:not(:disabled) {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.form-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style> 