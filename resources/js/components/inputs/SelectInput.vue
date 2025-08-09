<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ label }}</label>
    <select
      :id="id"
      :disabled="disabled"
      :value="modelValue"
      @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
      class="block w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-60 disabled:cursor-not-allowed"
    >
      <option v-if="placeholder" value="">{{ placeholder }}</option>
      <option v-for="opt in normalizedOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
    </select>
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Option { value: string; label: string }

type Options = Record<string, string> | Option[];

interface Props {
  id?: string;
  label?: string;
  modelValue: string;
  options: Options;
  placeholder?: string;
  disabled?: boolean;
  error?: string;
}

const props = defineProps<Props>();

const normalizedOptions = computed<Option[]>(() => {
  if (Array.isArray(props.options)) return props.options;
  return Object.entries(props.options).map(([value, label]) => ({ value, label }));
});

defineEmits<{ (e: 'update:modelValue', value: string): void }>();
</script>
