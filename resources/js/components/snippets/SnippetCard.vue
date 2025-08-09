<template>
  <div class="snippet-card">
    <div class="snippet-header">
      <div class="snippet-info">
        <h3 class="snippet-title">
          <Link :href="`/code/${snippet.hash}`" class="snippet-link">
            {{ snippet.content.substring(0, 50) }}...
          </Link>
        </h3>
        <div class="snippet-meta">
          <span class="snippet-language">{{ languageLabel }}</span>
          <slot name="meta" />
        </div>
      </div>
      <div class="snippet-actions">
        <slot name="actions">
          <Link :href="`/code/${snippet.hash}`" class="btn-secondary">Просмотреть</Link>
        </slot>
      </div>
    </div>
    <div class="snippet-preview">
      <pre><code ref="codeEl" class="hljs snippet-code" :class="hljsClass(snippet.language)">{{ snippet.content.substring(0, 200) }}...</code></pre>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onUpdated, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { LANGUAGE_OPTIONS } from '@/types';
import { hljsLanguageClass as hljsClass } from '@/composables/useHighlight';
import hljs from 'highlight.js';

interface Props { snippet: { hash: string; content: string; language: string; created_at?: string; access_count?: number; privacy?: string } }
const props = defineProps<Props>();

const languageLabel = computed(() => (LANGUAGE_OPTIONS as any)[props.snippet.language] || props.snippet.language);

const codeEl = ref<HTMLElement | null>(null);

onMounted(() => {
  // Ленивая подсветка
  const el = codeEl.value;
  if (!el || !('IntersectionObserver' in window)) return hljs.highlightElement(el as HTMLElement);
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        hljs.highlightElement(el as HTMLElement);
        io.disconnect();
      }
    });
  }, { rootMargin: '100px' });
  io.observe(el);
});

onUpdated(() => {
  if (codeEl.value) hljs.highlightElement(codeEl.value as HTMLElement);
});
</script>

<style scoped>
.snippet-card { background-color: var(--color-surface); backdrop-filter: blur(12px); border-radius: 1rem; padding: 1.5rem; border: 1px solid var(--color-border); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
.snippet-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
.snippet-title { font-size: 1.125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.5rem; }
.snippet-link { color: var(--color-primary); text-decoration: none; }
.snippet-link:hover { text-decoration: underline; }
.snippet-meta { display: flex; gap: 1rem; font-size: 0.875rem; color: var(--color-textSecondary); flex-wrap: wrap; }
.snippet-actions { display: flex; gap: 0.5rem; }
.snippet-preview { background-color: var(--color-surface); border-radius: 0.5rem; border: 1px solid var(--color-border); overflow: hidden; }
code.snippet-code { display: block; font-family: 'Monaco','Menlo','Ubuntu Mono',monospace; font-size: 0.875rem; line-height: 1.5; color: var(--color-textSecondary); white-space: pre-wrap; overflow: hidden; }
</style>
