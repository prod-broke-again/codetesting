<template>
  <button :type="type" :disabled="disabled || busy" :aria-disabled="(disabled || busy) ? 'true' : 'false'" :aria-busy="busy ? 'true' : 'false'" class="inline-flex items-center justify-center px-4 py-2 rounded-lg font-medium border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-60 disabled:cursor-not-allowed transition">
    <slot />
  </button>
</template>

<script setup lang="ts">
interface Props { disabled?: boolean; busy?: boolean; type?: 'button' | 'submit' | 'reset' }
withDefaults(defineProps<Props>(), { disabled: false, busy: false, type: 'button' });
</script>
