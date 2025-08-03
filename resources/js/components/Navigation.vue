<template>
    <nav class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Логотип -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <router-link to="/" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                CodeTesting.ru
                            </span>
                        </router-link>
                    </div>
                </div>

                <!-- Центральная навигация -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <router-link
                            to="/"
                            class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            :class="[
                                $route.path === '/' 
                                    ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' 
                                    : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400'
                            ]"
                        >
                            Главная
                        </router-link>
                        <router-link
                            to="/create"
                            class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            :class="[
                                $route.path === '/create' 
                                    ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' 
                                    : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400'
                            ]"
                        >
                            Создать
                        </router-link>
                    </div>
                </div>

                <!-- Правая часть навигации -->
                <div class="flex items-center space-x-4">
                    <!-- Переключатель темы -->
                    <ThemeToggle />
                    
                    <!-- Кнопки авторизации -->
                    <div v-if="!isAuthenticated" class="flex items-center space-x-2">
                        <router-link
                            to="/auth/login"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                        >
                            Войти
                        </router-link>
                        <router-link
                            to="/auth/register"
                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                        >
                            Регистрация
                        </router-link>
                    </div>

                    <!-- Профиль пользователя -->
                    <div v-else class="relative">
                        <button
                            @click="toggleUserMenu"
                            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200"
                        >
                            <img
                                v-if="user?.avatar"
                                :src="user.avatar"
                                :alt="user.name"
                                class="w-8 h-8 rounded-full"
                            />
                            <div
                                v-else
                                class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center"
                            >
                                <span class="text-white text-sm font-medium">
                                    {{ user?.name?.charAt(0)?.toUpperCase() }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ user?.name }}
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Выпадающее меню пользователя -->
                        <div
                            v-if="isUserMenuOpen"
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                        >
                            <div class="py-1">
                                <router-link
                                    to="/dashboard"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                >
                                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    Дашборд
                                </router-link>
                                <router-link
                                    to="/profile"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                >
                                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Профиль
                                </router-link>
                                <hr class="my-1 border-gray-200 dark:border-gray-700" />
                                <button
                                    @click="logout"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                >
                                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                    </svg>
                                    Выйти
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import ThemeToggle from './ThemeToggle.vue';

interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
}

// Props от Inertia.js
interface Props {
    user?: User;
}
const props = defineProps<Props>();

const isUserMenuOpen = ref(false);

const isAuthenticated = computed(() => {
    return !!props.user;
});

const user = computed(() => props.user);

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const logout = async () => {
    try {
        const response = await fetch('/auth/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        if (response.ok) {
            router.visit('/');
        }
    } catch (error) {
        console.error('Ошибка выхода:', error);
    }
};

const handleClickOutside = (event: Event) => {
    const target = event.target as Element;
    if (!target.closest('.relative')) {
        isUserMenuOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script> 