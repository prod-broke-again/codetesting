<template>
  <div v-if="open" class="modal-backdrop" @click.self="$emit('close')">
    <div class="modal-card">
      <div class="modal-header">
        <div class="modal-title"><slot name="title">Редактор кода</slot></div>
        <button class="modal-close" @click="$emit('close')">✕</button>
      </div>
      <div class="modal-body">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
          <div>
            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Язык</label>
            <SelectInput :model-value="localLanguage" @update:modelValue="(v:string)=> localLanguage=v" :options="languageOptionsArray" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Тема</label>
            <SelectInput :model-value="localTheme" @update:modelValue="(v:string)=> localTheme=v" :options="themeOptions" />
          </div>
          <div v-if="showToken">
            <TextInput v-model="localToken" label="Токен" placeholder="tk_..." />
          </div>
        </div>
        <div ref="editorEl" class="h-96 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden"></div>
      </div>
      <div class="modal-footer">
        <ButtonSecondary @click="$emit('close')">Отмена</ButtonSecondary>
        <ButtonPrimary :disabled="saving" :busy="saving" @click="onSave">Сохранить</ButtonPrimary>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, nextTick, onBeforeUnmount, computed } from 'vue';
import * as monaco from 'monaco-editor';
import ButtonPrimary from '@/components/buttons/ButtonPrimary.vue';
import ButtonSecondary from '@/components/buttons/ButtonSecondary.vue';
import SelectInput from '@/components/inputs/SelectInput.vue';
import TextInput from '@/components/inputs/TextInput.vue';

interface Props {
  open: boolean;
  value: string;
  language: string;
  theme: string;
  languageOptions: Record<string, string>;
  showToken?: boolean;
  token?: string;
  saving?: boolean;
}
const props = defineProps<Props>();
const emit = defineEmits<{ (e: 'update:value', v: string): void; (e: 'update:language', v: string): void; (e: 'update:theme', v: string): void; (e: 'update:token', v: string): void; (e: 'save'): void; (e: 'close'): void }>();

const editorEl = ref<HTMLElement | null>(null);
let editor: monaco.editor.IStandaloneCodeEditor | null = null;

const localValue = ref(props.value);
const localLanguage = ref(props.language);
const localTheme = ref(props.theme === 'vs-light' ? 'vs-light' : 'vs-dark');
const localToken = ref(props.token || '');

const languageOptionsArray = computed(() => Object.entries(props.languageOptions).map(([value, label]) => ({ value, label })));
const themeOptions = [ { value: 'vs-dark', label: 'VS Code Dark' }, { value: 'vs-light', label: 'VS Code Light' } ];

const toMonacoLanguage = (lang: string) => { const map: Record<string, string> = { php: 'php', javascript: 'javascript', typescript: 'typescript', python: 'python', java: 'java', cpp: 'cpp', csharp: 'csharp', html: 'html', css: 'css', sql: 'sql', bash: 'shell', json: 'json', xml: 'xml', markdown: 'markdown', vue: 'html', jsx: 'javascript', tsx: 'typescript', blade: 'php', 'php-html': 'php', 'php-blade': 'php', 'html-css': 'html', 'html-js': 'html' }; const key = (lang || '').toString().toLowerCase(); return map[key] || 'plaintext'; };
const toMonacoTheme = (theme: string) => (theme === 'vs-light' ? 'vs' : 'vs-dark');

const mountEditor = async () => {
  await nextTick();
  if (!editorEl.value) return;
  editor = monaco.editor.create(editorEl.value, { value: localValue.value, language: toMonacoLanguage(localLanguage.value), theme: toMonacoTheme(localTheme.value), automaticLayout: true, minimap: { enabled: false }, fontSize: 14, lineNumbers: 'on', scrollBeyondLastLine: false });
};
const disposeEditor = () => { if (editor) { editor.dispose(); editor = null; } };

watch(() => props.open, (val) => { if (val) mountEditor(); else disposeEditor(); });
watch(localLanguage, (val) => { const m = editor?.getModel(); if (m) monaco.editor.setModelLanguage(m, toMonacoLanguage(val)); emit('update:language', val); });
watch(localTheme, (val) => { monaco.editor.setTheme(toMonacoTheme(val)); emit('update:theme', val); });

const onSave = () => { if (!editor) return; emit('update:value', editor.getValue()); emit('update:token', localToken.value); emit('save'); };

onBeforeUnmount(disposeEditor);
</script>

<style scoped>
.modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 1rem; }
.modal-card { width: 100%; max-width: 960px; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: 0.75rem; overflow: hidden; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--color-border); }
.modal-title { font-weight: 600; }
.modal-close { background: transparent; border: none; font-size: 1.25rem; cursor: pointer; }
.modal-body { padding: 1rem; }
.modal-footer { padding: 0.75rem 1rem; display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid var(--color-border); }
</style>
