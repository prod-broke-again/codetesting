<template>
    <div id="app" class="min-h-screen bg-gray-50">
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-xl font-bold text-gray-900">
                            CodeTesting.ru
                        </Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link href="/">
                            <ButtonPrimary>Создать сниппет</ButtonPrimary>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ButtonPrimary from '@/components/buttons/ButtonPrimary.vue';
</script>

<style>
</style> 