import axios from 'axios';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

export const http = axios.create({
  baseURL: '/',
  withCredentials: true,
  headers: {
    Accept: 'application/json',
    'X-CSRF-TOKEN': csrfToken,
  },
});

http.interceptors.response.use(
  (response) => {
    // Поддержка ApiResponse { success, data, message } и прямых JSON-ответов
    const contentType = response.headers['content-type'] || '';
    if (contentType.includes('application/json')) {
      const payload = response.data;
      if (payload && typeof payload === 'object' && 'data' in payload) {
        return { ...response, data: (payload as any).data };
      }
    }
    return response;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export async function request<T>(method: 'GET' | 'POST' | 'PUT' | 'DELETE', url: string, data?: any, params?: any): Promise<T> {
  const res = await http.request<T>({ method, url, data, params });
  return res.data as T;
}
