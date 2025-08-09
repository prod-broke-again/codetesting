<template>
  <label class="inline-flex items-center gap-2 select-none cursor-pointer">
    <input
      type="checkbox"
      :checked="modelValue"
      @change="$emit('update:modelValue', ($event.target as HTMLInputElement).checked)"
      class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
    />
    <span class="text-sm text-gray-800 dark:text-gray-200">{{ label }}</span>
    <span v-if="description" class="text-xs text-gray-500">{{ description }}</span>
  </label>
</template>

<script setup lang="ts">
interface Props {
  modelValue: boolean;
  label: string;
  description?: string;
}

defineProps<Props>();

defineEmits<{ (e: 'update:modelValue', value: boolean): void }>();
</script>
