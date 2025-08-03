<template>
    <div class="relative">
        <button
            @click="toggleTheme"
            class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200"
            :title="currentTheme.name"
        >
            <!-- Солнце (светлая тема) -->
            <svg
                v-if="currentTheme.key === 'light'"
                class="w-5 h-5 text-yellow-500"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
            
            <!-- Луна (темная тема) -->
            <svg
                v-else-if="currentTheme.key === 'dark'"
                class="w-5 h-5 text-blue-400"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
            
            <!-- Космос (космическая тема) -->
            <svg
                v-else-if="currentTheme.key === 'space'"
                class="w-5 h-5 text-purple-400"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            
            <!-- Огонь (огненная тема) -->
            <svg
                v-else-if="currentTheme.key === 'fire'"
                class="w-5 h-5 text-orange-500"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <!-- Выпадающее меню тем -->
        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
        >
            <div class="py-1">
                <button
                    v-for="theme in themes"
                    :key="theme.key"
                    @click="selectTheme(theme)"
                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center space-x-3 transition-colors duration-150"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': currentTheme.key === theme.key }"
                >
                    <div class="w-4 h-4 rounded-full" :class="theme.color"></div>
                    <span class="text-sm font-medium" :class="theme.textColor">{{ theme.name }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';

interface Theme {
    key: string;
    name: string;
    color: string;
    textColor: string;
}

const themes: Theme[] = [
    {
        key: 'light',
        name: 'Светлая',
        color: 'bg-yellow-400',
        textColor: 'text-gray-900 dark:text-gray-100'
    },
    {
        key: 'dark',
        name: 'Темная',
        color: 'bg-blue-600',
        textColor: 'text-gray-900 dark:text-gray-100'
    },
    {
        key: 'space',
        name: 'Космос',
        color: 'bg-purple-600',
        textColor: 'text-gray-900 dark:text-gray-100'
    },
    {
        key: 'fire',
        name: 'Огонь',
        color: 'bg-orange-500',
        textColor: 'text-gray-900 dark:text-gray-100'
    }
];

const currentTheme = ref<Theme>(themes[0]);
const isOpen = ref(false);

const toggleTheme = () => {
    isOpen.value = !isOpen.value;
};

const selectTheme = (theme: Theme) => {
    currentTheme.value = theme;
    isOpen.value = false;
    
    // Применяем тему к документу
    document.documentElement.className = `theme-${theme.key}`;
    localStorage.setItem('theme', theme.key);
};

const handleClickOutside = (event: Event) => {
    const target = event.target as Element;
    if (!target.closest('.relative')) {
        isOpen.value = false;
    }
};

onMounted(() => {
    // Загружаем сохраненную тему
    const savedTheme = localStorage.getItem('theme') || 'light';
    const theme = themes.find(t => t.key === savedTheme) || themes[0];
    selectTheme(theme);
    
    // Добавляем обработчик клика вне компонента
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script> 