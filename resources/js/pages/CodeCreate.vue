<template>
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Создать новый сниппет
            </h1>
            <p class="text-xl text-gray-600">
                Создайте зашифрованный сниппет кода для обмена с коллегами
            </p>
        </div>

        <!-- Форма создания сниппета -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form @submit.prevent="createSnippet" class="space-y-4">
                <!-- Выбор языка -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Язык программирования
                    </label>
                    <select 
                        v-model="form.language"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="php">PHP</option>
                        <option value="javascript">JavaScript</option>
                        <option value="typescript">TypeScript</option>
                        <option value="python">Python</option>
                        <option value="java">Java</option>
                        <option value="csharp">C#</option>
                        <option value="cpp">C++</option>
                        <option value="go">Go</option>
                        <option value="rust">Rust</option>
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="sql">SQL</option>
                    </select>
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
                        <option value="vs-dark">Dark (VS Code)</option>
                        <option value="vs-light">Light (VS Code)</option>
                        <option value="monokai">Monokai</option>
                        <option value="github">GitHub</option>
                        <option value="dracula">Dracula</option>
                        <option value="solarized-dark">Solarized Dark</option>
                        <option value="solarized-light">Solarized Light</option>
                    </select>
                </div>

                <!-- Редактор кода -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Код
                    </label>
                    <textarea 
                        v-model="form.content"
                        rows="12"
                        placeholder="Введите ваш код здесь..."
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                    ></textarea>
                </div>

                <!-- Дополнительные опции -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                v-model="form.is_encrypted"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="ml-2 text-sm text-gray-700">Включить шифрование</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Время истечения
                        </label>
                        <select 
                            v-model="form.expires_at"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Без ограничений</option>
                            <option value="1">1 день</option>
                            <option value="7">7 дней</option>
                            <option value="30">30 дней</option>
                        </select>
                    </div>
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
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import type { CreateSnippetForm, ProgrammingLanguage, CodeTheme } from '@/types';

const router = useRouter();
const isLoading = ref<boolean>(false);

const form = reactive<CreateSnippetForm & { is_encrypted: boolean; expires_at: string }>({
    content: '',
    language: 'php' as ProgrammingLanguage,
    theme: 'vs-dark' as CodeTheme,
    is_encrypted: false,
    expires_at: ''
});

const createSnippet = async (): Promise<void> => {
    if (!form.content.trim()) {
        alert('Введите код');
        return;
    }

    isLoading.value = true;
    
    try {
        // Подготовка данных для отправки
        const requestData = {
            content: form.content,
            language: form.language,
            theme: form.theme,
            is_encrypted: form.is_encrypted,
            expires_at: form.expires_at ? getExpirationDate(form.expires_at) : null
        };

        const response = await fetch('/api/codes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(requestData)
        });

        if (response.ok) {
            const data = await response.json();
            
            // Показываем результат пользователю
            if (data.data.edit_token) {
                alert(`Сниппет создан!\n\nСсылка: ${data.data.url}\nТокен для редактирования: ${data.data.edit_token}\n\nСохраните токен! Он нужен для редактирования сниппета.`);
            } else {
                alert('Сниппет создан успешно!');
            }
            
            // Переходим к просмотру сниппета
            router.push(`/code/${data.data.hash}`);
        } else {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Ошибка создания сниппета');
        }
    } catch (error) {
        console.error('Error:', error);
        alert(error instanceof Error ? error.message : 'Ошибка создания сниппета');
    } finally {
        isLoading.value = false;
    }
};

const getExpirationDate = (days: string): string => {
    const date = new Date();
    date.setDate(date.getDate() + parseInt(days));
    return date.toISOString();
};
</script> 