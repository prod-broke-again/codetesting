<template>
  <button :type="type" :disabled="disabled" class="inline-flex items-center justify-center px-4 py-2 rounded-lg font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow hover:shadow-md hover:from-blue-700 hover:to-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed transition">
    <slot />
  </button>
</template>

<script setup lang="ts">
interface Props { disabled?: boolean; type?: 'button' | 'submit' | 'reset' }
withDefaults(defineProps<Props>(), { disabled: false, type: 'button' });
</script>
