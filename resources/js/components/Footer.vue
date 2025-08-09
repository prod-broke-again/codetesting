<template>
  <footer class="mt-auto bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Верхняя часть -->
      <div class="py-12 grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-5">
        <!-- Бренд -->
        <div class="lg:col-span-1">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-semibold">CT</div>
            <span class="text-lg font-bold bg-gradient-to-r from-blue-500 to-indigo-500 bg-clip-text text-transparent">CodeTesting.ru</span>
          </div>
          <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 max-w-xs">Безопасный обмен кодом</p>
          <div class="mt-4 flex items-center gap-2">
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-blue-600 hover:text-white transition">GitHub</a>
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-blue-600 hover:text-white transition">Twitter</a>
            <a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-blue-600 hover:text-white transition">Discord</a>
          </div>
        </div>

        <!-- Колонки ссылок -->
        <div class="lg:col-span-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
          <div>
            <h4 class="text-xs font-semibold tracking-wider uppercase text-gray-800 dark:text-gray-200 mb-3">Продукт</h4>
            <ul class="space-y-2 text-sm">
              <li><Link href="/explore" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Обзор сниппетов</Link></li>
              <li><Link href="/trending" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Популярное</Link></li>
              <li><Link href="/languages" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Языки программирования</Link></li>
              <li><Link href="/themes" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Темы оформления</Link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-xs font-semibold tracking-wider uppercase text-gray-800 dark:text-gray-200 mb-3">Разработчикам</h4>
            <ul class="space-y-2 text-sm">
              <li><Link href="/api" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">API документация</Link></li>
              <li><Link href="/sdk" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">SDK</Link></li>
              <li><Link href="/plugins" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Плагины</Link></li>
              <li><Link href="/integrations" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Интеграции</Link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-xs font-semibold tracking-wider uppercase text-gray-800 dark:text-gray-200 mb-3">Поддержка</h4>
            <ul class="space-y-2 text-sm">
              <li><Link href="/help" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Помощь</Link></li>
              <li><Link href="/docs" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Документация</Link></li>
              <li><Link href="/contact" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Контакты</Link></li>
              <li><Link href="/status" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Статус сервиса</Link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-xs font-semibold tracking-wider uppercase text-gray-800 dark:text-gray-200 mb-3">Компания</h4>
            <ul class="space-y-2 text-sm">
              <li><Link href="/about" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">О нас</Link></li>
              <li><Link href="/blog" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Блог</Link></li>
              <li><Link href="/careers" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Карьера</Link></li>
              <li><Link href="/press" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Пресса</Link></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Нижняя полоса -->
      <div class="border-t border-gray-200 dark:border-gray-700 py-6 flex flex-col md:flex-row items-center md:justify-between gap-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">© {{ new Date().getFullYear() }} CodeTesting.ru</p>
        <div class="flex items-center gap-6 text-sm">
          <Link href="/privacy" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Политика конфиденциальности</Link>
          <Link href="/terms" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Условия использования</Link>
          <Link href="/cookies" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Cookies</Link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
/* кастомные стили не требуются — используется Tailwind */
</style> 