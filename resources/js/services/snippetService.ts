import { request } from '@/http/client';
import { SnippetSchema, type Snippet } from '@/schemas/snippet';
import { mapSnippet } from '@/mappers/snippetMapper';

export interface CreateSnippetPayload {
  content: string;
  language: string;
  theme: string;
  is_encrypted?: boolean;
  privacy?: string;
  password?: string;
  expires_at?: string;
}

export interface UpdateSnippetPayload {
  content: string;
  language: string;
  theme: string;
  edit_token?: string;
  is_encrypted?: boolean;
  expires_at?: string | null;
}

export async function createSnippet(payload: CreateSnippetPayload, createUrl: string = '/api/snippets'): Promise<Snippet> {
  const data = await request<any>('POST', createUrl, payload);
  const parsed = SnippetSchema.passthrough().safeParse(data);
  if (!parsed.success) throw new Error('Invalid snippet response');
  return mapSnippet(parsed.data);
}

export async function getSnippet(hash: string): Promise<Snippet> {
  const data = await request<any>('GET', `/api/snippets/${hash}`);
  const parsed = SnippetSchema.passthrough().safeParse(data);
  if (!parsed.success) throw new Error('Invalid snippet response');
  return mapSnippet(parsed.data);
}

export async function updateSnippet(hash: string, payload: UpdateSnippetPayload): Promise<Snippet> {
  const data = await request<any>('PUT', `/api/snippets/${hash}`, payload);
  const parsed = SnippetSchema.passthrough().safeParse(data);
  if (!parsed.success) throw new Error('Invalid snippet response');
  return mapSnippet(parsed.data);
}

export async function deleteSnippet(hash: string, edit_token?: string): Promise<void> {
  await request<any>('DELETE', `/api/snippets/${hash}`, { edit_token });
}
