<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ label }}</label>
    <textarea
      :id="id"
      :rows="rows"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      :aria-invalid="error ? 'true' : 'false'"
      :aria-describedby="error ? `${id}-error` : undefined"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
      class="block w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-60 disabled:cursor-not-allowed font-mono text-sm"
    />
    <p v-if="error" :id="`${id}-error`" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
interface Props {
  id?: string;
  label?: string;
  modelValue: string;
  rows?: number;
  placeholder?: string;
  disabled?: boolean;
  required?: boolean;
  error?: string;
}
withDefaults(defineProps<Props>(), { rows: 6, disabled: false, required: false });

defineEmits<{ (e: 'update:modelValue', value: string): void }>();
</script>
