<template>
  <div class="toast-container">
    <transition-group name="toast" tag="div">
      <div v-for="t in toasts" :key="t.id" class="toast" :class="`toast-${t.type}`" @click="dismiss(t.id)">
        {{ t.message }}
      </div>
    </transition-group>
  </div>
</template>

<script setup lang="ts">
import { useToast } from '@/composables/useToast';
const { toasts, dismiss } = useToast();
</script>

<style scoped>
.toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 100; display: flex; flex-direction: column; gap: 0.5rem; }
.toast-enter-active, .toast-leave-active { transition: all 0.2s; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(-6px); }
.toast { padding: 0.5rem 0.75rem; border-radius: 0.5rem; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; }
.toast-success { background-color: #16a34a; }
.toast-error { background-color: #dc2626; }
.toast-info { background-color: #2563eb; }
.toast-warning { background-color: #d97706; }
</style>
