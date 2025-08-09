import type { Snippet } from '@/schemas/snippet';

export function mapSnippet(input: any): Snippet {
  return {
    id: input.id,
    hash: String(input.hash),
    content: String(input.content ?? ''),
    language: String(input.language),
    theme: String(input.theme ?? 'vs-dark'),
    is_encrypted: Boolean(input.is_encrypted),
    privacy: input.privacy ? String(input.privacy) : undefined,
    user_id: typeof input.user_id === 'number' ? input.user_id : undefined,
    is_guest: Boolean(input.is_guest),
    edit_token: typeof input.edit_token === 'string' ? input.edit_token : undefined,
    access_count: Number(input.access_count ?? 0),
    last_accessed_at: input.last_accessed_at ?? null,
    expires_at: input.expires_at ?? null,
    created_at: input.created_at,
    updated_at: input.updated_at,
  };
}
