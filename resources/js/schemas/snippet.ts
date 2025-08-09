import { z } from 'zod';

export const SnippetSchema = z.object({
  id: z.number().optional(),
  hash: z.string(),
  content: z.string(),
  language: z.string(),
  theme: z.string(),
  is_encrypted: z.boolean().optional().default(false),
  privacy: z.string().optional(),
  user_id: z.number().nullable().optional(),
  is_guest: z.boolean().optional().default(false),
  edit_token: z.string().optional(),
  access_count: z.number().optional().default(0),
  last_accessed_at: z.string().nullable().optional(),
  expires_at: z.string().nullable().optional(),
  created_at: z.string().optional(),
  updated_at: z.string().optional(),
});

export type Snippet = z.infer<typeof SnippetSchema>;
