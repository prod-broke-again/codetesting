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
                        <TextInput id="name" v-model="form.name" label="Имя" placeholder="Ваше имя" />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <TextInput id="email" v-model="form.email" type="email" label="Email" placeholder="your@email.com" />
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.email }}
                        </p>
                    </div>

                    <!-- Пароль -->
                    <div>
                        <TextInput id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" label="Пароль" placeholder="Ваш пароль" />
                        <p v-if="errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.password }}
                        </p>
                    </div>

                    <!-- Подтверждение пароля (только для регистрации) -->
                    <div v-if="!isLoginMode">
                        <TextInput id="password_confirmation" v-model="form.password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" label="Подтвердите пароль" placeholder="Повторите пароль" />
                        <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- Запомнить меня (только для входа) -->
                    <div v-if="isLoginMode" class="flex items-center justify-between">
                        <CheckboxInput v-model="form.remember" label="Запомнить меня" />
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                Забыли пароль?
                            </a>
                        </div>
                    </div>

                    <!-- Кнопка отправки -->
                    <div>
                        <ButtonPrimary type="submit" :disabled="isLoading" class="w-full">{{ isLoading ? (isLoginMode ? 'Вход...' : 'Регистрация...') : (isLoginMode ? 'Войти' : 'Зарегистрироваться') }}</ButtonPrimary>
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
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { z } from 'zod';
import { useToast } from '@/composables/useToast';
import TextInput from '@/components/inputs/TextInput.vue';
import CheckboxInput from '@/components/inputs/CheckboxInput.vue';
import ButtonPrimary from '@/components/buttons/ButtonPrimary.vue';
import { UserIcon, EnvelopeIcon, LockClosedIcon, EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';

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
const { show: toast } = useToast();

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

const handleSubmit = async () => {
    if (!validateForm()) return;
    isLoading.value = true;
    try {
        const data = isLoginMode.value 
            ? { email: form.email, password: form.password, remember: form.remember }
            : form;

        const endpoint = isLoginMode.value ? '/auth/login' : '/auth/register';
        
        // Используем Inertia для отправки формы
        router.post(endpoint, data, {
            onSuccess: () => toast(isLoginMode.value ? 'Вы успешно вошли' : 'Аккаунт создан', 'success'),
            onError: () => toast('Проверьте правильность введенных данных', 'error'),
            onFinish: () => { isLoading.value = false; },
        });
    } catch (e) {
        console.error(e);
        toast('Ошибка соединения с сервером', 'error');
        isLoading.value = false;
    }
};

const loginWithGoogle = () => {
    window.location.href = '/auth/google';
};

const loginWithGithub = () => {
    window.location.href = '/auth/github';
};
</script> 