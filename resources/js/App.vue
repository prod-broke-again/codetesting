<template>
    <div id="app" class="min-h-screen bg-gray-50">
        <!-- Навигация -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <router-link to="/" class="text-xl font-bold text-gray-900">
                            CodeTesting.ru
                        </router-link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <router-link 
                            to="/create" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                        >
                            Создать сниппет
                        </router-link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Основной контент -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <router-view />
        </main>
    </div>
</template>

<script setup>
// Composition API
import { onMounted } from 'vue';

onMounted(() => {
    console.log('App mounted');
});
</script>

<style>
/* Глобальные стили */
</style> 