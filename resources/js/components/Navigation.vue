<template>
    <nav class="nav-container">
        <div class="nav-content">
            <div class="nav-header">
                <!-- Логотип -->
                <div class="nav-logo">
                    <a href="/" class="logo-link">
                        <div class="logo-icon">
                            <svg class="logo-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="logo-text">
                            CodeTesting.ru
                        </span>
                    </a>
                </div>

                <!-- Поиск -->
                <div class="nav-search">
                    <div class="search-container">
                        <div class="search-icon">
                            <svg class="search-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            v-model="searchQuery"
                            @input="onSearchInput"
                            @keyup.enter="performSearch"
                            placeholder="Поиск сниппетов..."
                            class="search-input"
                        />
                        <button
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="search-clear"
                            title="Очистить поиск"
                        >
                            <svg class="search-clear-svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Центральная навигация -->
                <div class="nav-menu">
                    <div class="nav-links">
                        <a href="/" class="nav-link" :class="{ 'nav-link-active': $page.url === '/' }">
                            <svg class="nav-link-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            Главная
                        </a>
                        <a href="/explore" class="nav-link" :class="{ 'nav-link-active': $page.url === '/explore' }">
                            <svg class="nav-link-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Обзор
                        </a>
                        <a href="/my-snippets" class="nav-link" :class="{ 'nav-link-active': $page.url === '/my-snippets' }">
                            <svg class="nav-link-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            Мои сниппеты
                        </a>
                    </div>
                </div>

                <!-- Правая часть навигации -->
                <div class="nav-actions">
                    <!-- Переключатель темы -->
                    <ThemeToggle />
                    
                    <!-- Кнопки авторизации -->
                    <div v-if="!isAuthenticated" class="auth-buttons">
                        <a href="/auth" class="auth-link">
                            Войти
                        </a>
                        <a href="/auth" class="auth-button">
                            Регистрация
                        </a>
                    </div>

                    <!-- Пользовательское меню -->
                    <div v-if="user" class="user-menu">
                        <Menu as="div" class="relative">
                            <MenuButton class="user-button">
                                <img 
                                    v-if="user.avatar" 
                                    :src="user.avatar" 
                                    :alt="user.name"
                                    class="user-avatar"
                                />
                                <div v-else class="user-avatar-placeholder">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <span class="user-name">{{ user.name }}</span>
                                <ChevronDownIcon class="user-dropdown-icon" />
                            </MenuButton>

                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems class="user-dropdown">
                                    <MenuItem v-slot="{ active }">
                                        <a href="/dashboard" :class="[active ? 'dropdown-item-active' : 'dropdown-item']">
                                            <ChartBarIcon class="dropdown-icon" />
                                            Дашборд
                                        </a>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <a href="/my-snippets" :class="[active ? 'dropdown-item-active' : 'dropdown-item']">
                                            <CodeBracketIcon class="dropdown-icon" />
                                            Мои сниппеты
                                        </a>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <a href="/profile" :class="[active ? 'dropdown-item-active' : 'dropdown-item']">
                                            <UserIcon class="dropdown-icon" />
                                            Профиль
                                        </a>
                                    </MenuItem>
                                    <div class="dropdown-divider"></div>
                                    <MenuItem v-slot="{ active }">
                                        <form @submit.prevent="logout" class="w-full">
                                            <button type="submit" :class="[active ? 'dropdown-item-active' : 'dropdown-item', 'w-full text-left']">
                                                <ArrowRightOnRectangleIcon class="dropdown-icon" />
                                                Выйти
                                            </button>
                                        </form>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import ThemeToggle from './ThemeToggle.vue';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { ChevronDownIcon, ChartBarIcon, CodeBracketIcon, UserIcon, ArrowRightOnRectangleIcon } from '@heroicons/vue/24/outline';

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

const page = usePage();
const isUserMenuOpen = ref(false);
const searchQuery = ref('');

const isAuthenticated = computed(() => {
    return !!props.user;
});

const user = computed(() => props.user);

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const onSearchInput = () => {
    // Debounce поиска
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        performSearch();
    }, 300);
};

let searchTimeout: number | null = null;

const performSearch = () => {
    if (searchQuery.value.trim()) {
        router.visit(`/search?q=${encodeURIComponent(searchQuery.value.trim())}`);
    }
};

const clearSearch = () => {
    searchQuery.value = '';
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
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
    if (!target.closest('.user-menu')) {
        isUserMenuOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
});
</script>

<style scoped>
.nav-container {
    background-color: var(--color-surface);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--color-border);
    position: sticky;
    top: 0;
    z-index: 50;
}

.nav-content {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
}

@media (min-width: 640px) {
    .nav-content {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .nav-content {
        padding: 0 2rem;
    }
}

.nav-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 4rem;
    gap: 1rem;
}

.nav-logo {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.logo-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.logo-icon {
    width: 2rem;
    height: 2rem;
    background: var(--gradient-primary);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.logo-svg {
    width: 1.25rem;
    height: 1.25rem;
    color: white;
}

.logo-text {
    font-size: 1.25rem;
    font-weight: 700;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.nav-search {
    flex: 1;
    max-width: 24rem;
    margin: 0 1rem;
}

.search-container {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    color: var(--color-textSecondary);
    z-index: 10;
}

.search-svg {
    width: 1.25rem;
    height: 1.25rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid var(--color-border);
    border-radius: 0.75rem;
    background-color: var(--color-surface);
    color: var(--color-text);
    font-size: 0.875rem;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-input::placeholder {
    color: var(--color-textSecondary);
}

.search-clear {
    position: absolute;
    right: 0.5rem;
    background: none;
    border: none;
    color: var(--color-textSecondary);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: all 0.2s;
}

.search-clear:hover {
    color: var(--color-text);
    background-color: var(--color-border);
}

.search-clear-svg {
    width: 1rem;
    height: 1rem;
}

.nav-menu {
    display: none;
}

@media (min-width: 1024px) {
    .nav-menu {
        display: block;
    }
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    color: var(--color-textSecondary);
}

.nav-link:hover {
    color: var(--color-text);
    background-color: var(--color-border);
}

.nav-link-active {
    color: var(--color-primary);
    background-color: rgba(59, 130, 246, 0.1);
}

.nav-link-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.auth-buttons {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.auth-link {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-textSecondary);
    text-decoration: none;
    transition: all 0.2s;
}

.auth-link:hover {
    color: var(--color-primary);
}

.auth-button {
    padding: 0.5rem 1rem;
    background: var(--gradient-primary);
    color: white;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.auth-button:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.user-menu {
    position: relative;
}

.user-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
    background: none;
    border: none;
    cursor: pointer;
}

.user-button:hover {
    background-color: var(--color-border);
}

.user-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
}

.user-avatar-placeholder {
    width: 2rem;
    height: 2rem;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar-text {
    color: white;
    font-size: 0.875rem;
    font-weight: 500;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text);
}

.user-arrow {
    width: 1rem;
    height: 1rem;
    color: var(--color-textSecondary);
}

.user-dropdown {
    position: absolute;
    right: 0;
    margin-top: 0.5rem;
    width: 12rem;
    background-color: var(--color-surface);
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--color-border);
    z-index: 50;
}

.dropdown-content {
    padding: 0.25rem 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    color: var(--color-text);
    text-decoration: none;
    transition: all 0.15s;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
}

.dropdown-item:hover {
    background-color: var(--color-border);
}

.dropdown-icon {
    width: 1rem;
    height: 1rem;
    margin-right: 0.75rem;
}

.dropdown-divider {
    margin: 0.25rem 0;
    border: none;
    border-top: 1px solid var(--color-border);
}

.dropdown-item-danger {
    color: var(--color-error);
}

.dropdown-item-danger:hover {
    background-color: rgba(239, 68, 68, 0.1);
}

/* Мобильная адаптация */
@media (max-width: 1023px) {
    .nav-search {
        display: none;
    }
    
    .nav-header {
        gap: 0.5rem;
    }
}

@media (max-width: 768px) {
    .user-name {
        display: none;
    }
    
    .auth-buttons {
        gap: 0.25rem;
    }
    
    .auth-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .auth-button {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
}
</style> 