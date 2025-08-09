import type { Snippet } from '@/schemas/snippet';

const tokenKey = (hash: string) => `edit_token:${hash}`;

export const snippetRepository = {
  saveGuestToken(hash: string, token: string) {
    try { localStorage.setItem(tokenKey(hash), token); } catch {}
  },
  getGuestToken(hash: string): string | null {
    try { return localStorage.getItem(tokenKey(hash)); } catch { return null; }
  },
  forgetGuestToken(hash: string) {
    try { localStorage.removeItem(tokenKey(hash)); } catch {}
  },
  cache: new Map<string, Snippet>(),
  getCached(hash: string): Snippet | undefined { return this.cache.get(hash); },
  setCached(snippet: Snippet) { this.cache.set(snippet.hash, snippet); },
};
