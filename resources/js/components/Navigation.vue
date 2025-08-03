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

                <!-- Центральная навигация -->
                <div class="nav-menu">
                    <div class="nav-links">
                        <a
                            href="/"
                            class="nav-link"
                            :class="[
                                $page.url === '/' ? 'nav-link-active' : 'nav-link-inactive'
                            ]"
                        >
                            Главная
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

                    <!-- Профиль пользователя -->
                    <div v-else class="user-menu">
                        <button @click="toggleUserMenu" class="user-button">
                            <img
                                v-if="user?.avatar"
                                :src="user.avatar"
                                :alt="user.name"
                                class="user-avatar"
                            />
                            <div v-else class="user-avatar-placeholder">
                                <span class="user-avatar-text">
                                    {{ user?.name?.charAt(0)?.toUpperCase() }}
                                </span>
                            </div>
                            <span class="user-name">
                                {{ user?.name }}
                            </span>
                            <svg class="user-arrow" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Выпадающее меню пользователя -->
                        <div v-if="isUserMenuOpen" class="user-dropdown">
                            <div class="dropdown-content">
                                <a href="/dashboard" class="dropdown-item">
                                    <svg class="dropdown-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    Дашборд
                                </a>
                                <a href="/profile" class="dropdown-item">
                                    <svg class="dropdown-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Профиль
                                </a>
                                <hr class="dropdown-divider" />
                                <button @click="logout" class="dropdown-item dropdown-item-danger">
                                    <svg class="dropdown-icon" fill="currentColor" viewBox="0 0 20 20">
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
import { router, usePage } from '@inertiajs/vue3';
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

const page = usePage();
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
    if (!target.closest('.user-menu')) {
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
}

.nav-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
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

.nav-menu {
    display: none;
}

@media (min-width: 768px) {
    .nav-menu {
        display: block;
    }
}

.nav-links {
    margin-left: 2.5rem;
    display: flex;
    align-items: baseline;
    gap: 1rem;
}

.nav-link {
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    text-decoration: none;
}

.nav-link-active {
    background-color: var(--color-primary);
    color: white;
}

.nav-link-inactive {
    color: var(--color-textSecondary);
}

.nav-link-inactive:hover {
    color: var(--color-primary);
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
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
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.auth-button:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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
</style> 