<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Логотип и заголовок -->
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold gradient-text">
                    {{ isLoginMode ? 'Войти в аккаунт' : 'Создать аккаунт' }}
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ isLoginMode ? 'Войдите в свой аккаунт для доступа к функциям' : 'Создайте новый аккаунт для начала работы' }}
                </p>
            </div>

            <!-- Переключатель режимов -->
            <div class="flex items-center justify-center">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                    <button
                        @click="setLoginMode(true)"
                        :class="[
                            'px-4 py-2 text-sm font-medium rounded-md transition-all duration-200',
                            isLoginMode 
                                ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' 
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                        ]"
                    >
                        Вход
                    </button>
                    <button
                        @click="setLoginMode(false)"
                        :class="[
                            'px-4 py-2 text-sm font-medium rounded-md transition-all duration-200',
                            !isLoginMode 
                                ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' 
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                        ]"
                    >
                        Регистрация
                    </button>
                </div>
            </div>

            <!-- Форма -->
            <div class="card p-8">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Имя (только для регистрации) -->
                    <div v-if="!isLoginMode">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Имя
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <UserIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="input-field pl-10"
                                placeholder="Ваше имя"
                                :class="{ 'border-red-500': errors.name }"
                            />
                        </div>
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="input-field pl-10"
                                placeholder="your@email.com"
                                :class="{ 'border-red-500': errors.email }"
                            />
                        </div>
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.email }}
                        </p>
                    </div>

                    <!-- Пароль -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Пароль
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <LockClosedIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                class="input-field pl-10 pr-10"
                                placeholder="Ваш пароль"
                                :class="{ 'border-red-500': errors.password }"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <EyeIcon v-if="showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                <EyeSlashIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                            </button>
                        </div>
                        <p v-if="errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.password }}
                        </p>
                    </div>

                    <!-- Подтверждение пароля (только для регистрации) -->
                    <div v-if="!isLoginMode">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Подтвердите пароль
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <LockClosedIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                required
                                class="input-field pl-10 pr-10"
                                placeholder="Повторите пароль"
                                :class="{ 'border-red-500': errors.password_confirmation }"
                            />
                            <button
                                type="button"
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <EyeIcon v-if="showConfirmPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                <EyeSlashIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                            </button>
                        </div>
                        <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- Запомнить меня (только для входа) -->
                    <div v-if="isLoginMode" class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                Запомнить меня
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                Забыли пароль?
                            </a>
                        </div>
                    </div>

                    <!-- Кнопка отправки -->
                    <div>
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="btn-primary w-full flex items-center justify-center space-x-2"
                        >
                            <div v-if="isLoading" class="flex items-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ isLoading ? (isLoginMode ? 'Вход...' : 'Регистрация...') : (isLoginMode ? 'Войти' : 'Зарегистрироваться') }}</span>
                            </div>
                            <div v-else class="flex items-center space-x-2">
                                <span>{{ isLoginMode ? 'Войти' : 'Зарегистрироваться' }}</span>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Разделитель -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-600" />
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                Или продолжите через
                            </span>
                        </div>
                    </div>

                    <!-- Социальные кнопки -->
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            @click="loginWithGoogle"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Google
                        </button>

                        <button
                            type="button"
                            @click="loginWithGithub"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
                            </svg>
                            GitHub
                        </button>
                    </div>
                </div>
            </div>

            <!-- Уведомления -->
            <TransitionRoot appear :show="!!notification" as="template">
                <Dialog as="div" @close="closeNotification" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
                                    <div class="flex items-center space-x-3">
                                        <div v-if="notification?.type === 'success'" class="flex-shrink-0">
                                            <CheckCircleIcon class="h-6 w-6 text-green-400" />
                                        </div>
                                        <div v-else-if="notification?.type === 'error'" class="flex-shrink-0">
                                            <ExclamationCircleIcon class="h-6 w-6 text-red-400" />
                                        </div>
                                        <div class="flex-1">
                                            <DialogTitle as="h3" class="text-lg font-medium text-gray-900 dark:text-white">
                                                {{ notification?.title }}
                                            </DialogTitle>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ notification?.message }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button
                                            type="button"
                                            class="btn-secondary w-full"
                                            @click="closeNotification"
                                        >
                                            Закрыть
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { z } from 'zod';
import { useVModel } from '@vueuse/core';
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionRoot,
    TransitionChild,
} from '@headlessui/vue';
import {
    UserIcon,
    EnvelopeIcon,
    LockClosedIcon,
    EyeIcon,
    EyeSlashIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
} from '@heroicons/vue/24/outline';

// Схемы валидации
const loginSchema = z.object({
    email: z.string().email('Введите корректный email'),
    password: z.string().min(6, 'Пароль должен содержать минимум 6 символов'),
    remember: z.boolean().default(false),
});

const registerSchema = z.object({
    name: z.string().min(2, 'Имя должно содержать минимум 2 символа'),
    email: z.string().email('Введите корректный email'),
    password: z.string().min(6, 'Пароль должен содержать минимум 6 символов'),
    password_confirmation: z.string(),
    remember: z.boolean().default(false),
}).refine((data) => data.password === data.password_confirmation, {
    message: "Пароли не совпадают",
    path: ["password_confirmation"],
});

// Состояние
const isLoginMode = ref(true);
const isLoading = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const notification = ref<{
    type: 'success' | 'error';
    title: string;
    message: string;
} | null>(null);

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    remember: false,
});

const errors = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Вычисляемые свойства
const currentSchema = computed(() => isLoginMode.value ? loginSchema : registerSchema);

// Методы
const setLoginMode = (mode: boolean) => {
    isLoginMode.value = mode;
    clearForm();
    clearErrors();
};

const clearForm = () => {
    Object.assign(form, {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        remember: false,
    });
};

const clearErrors = () => {
    Object.assign(errors, {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });
};

const validateForm = () => {
    try {
        const data = isLoginMode.value 
            ? { email: form.email, password: form.password, remember: form.remember }
            : form;
        
        currentSchema.value.parse(data);
        clearErrors();
        return true;
    } catch (error) {
        if (error instanceof z.ZodError) {
            clearErrors();
            error.errors.forEach((err) => {
                const field = err.path[0] as keyof typeof errors;
                if (field in errors) {
                    errors[field] = err.message;
                }
            });
        }
        return false;
    }
};

const showNotification = (type: 'success' | 'error', title: string, message: string) => {
    notification.value = { type, title, message };
};

const closeNotification = () => {
    notification.value = null;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }

    isLoading.value = true;

    try {
        const endpoint = isLoginMode.value ? '/auth/login' : '/auth/register';
        const data = isLoginMode.value 
            ? { email: form.email, password: form.password, remember: form.remember }
            : form;

        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Fingerprint': localStorage.getItem('fingerprint') || ''
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            showNotification('success', 'Успешно!', isLoginMode.value ? 'Вы успешно вошли в систему' : 'Аккаунт успешно создан');
            setTimeout(() => {
                router.visit('/');
            }, 1500);
        } else {
            showNotification('error', 'Ошибка', result.message || 'Произошла ошибка');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        showNotification('error', 'Ошибка', 'Ошибка соединения с сервером');
    } finally {
        isLoading.value = false;
    }
};

const loginWithGoogle = () => {
    window.location.href = '/auth/google';
};

const loginWithGithub = () => {
    window.location.href = '/auth/github';
};

// Автоматическое закрытие уведомлений
watch(notification, (newNotification) => {
    if (newNotification) {
        setTimeout(() => {
            closeNotification();
        }, 5000);
    }
});
</script> 