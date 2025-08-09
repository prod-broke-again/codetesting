import { reactive } from 'vue';

export type ToastType = 'success' | 'error' | 'info' | 'warning';

export interface ToastItem {
  id: number;
  type: ToastType;
  message: string;
  timeout?: number;
}

const state = reactive<{ toasts: ToastItem[] }>({ toasts: [] });
let idSeq = 1;

export function useToast() {
  const show = (message: string, type: ToastType = 'info', timeout = 3000) => {
    const item: ToastItem = { id: idSeq++, type, message, timeout };
    state.toasts.push(item);
    if (timeout > 0) {
      setTimeout(() => dismiss(item.id), timeout);
    }
  };

  const dismiss = (id: number) => {
    const idx = state.toasts.findIndex(t => t.id === id);
    if (idx !== -1) state.toasts.splice(idx, 1);
  };

  return { toasts: state.toasts, show, dismiss };
}
