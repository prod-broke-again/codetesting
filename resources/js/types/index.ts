// Типы для сниппетов кода
export interface CodeSnippet {
    id: number;
    hash: string;
    content: string;
    language: string;
    theme: string;
    is_encrypted: boolean;
    user_id?: number;
    is_guest: boolean;
    edit_token?: string;
    access_count: number;
    last_accessed_at?: string;
    expires_at?: string;
    fingerprint_id?: number;
    created_at: string;
    updated_at: string;
}

// Типы для создания сниппета
export interface CreateSnippetRequest {
    content: string;
    language: string;
    theme: string;
    is_encrypted?: boolean;
    expires_at?: string;
}

// Типы для API ответов
export interface ApiResponse<T> {
    data: T;
    message?: string;
    success: boolean;
}

// Типы для пользователя
export interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
}

// Типы для fingerprint
export interface Fingerprint {
    id: number;
    fingerprint_hash: string;
    user_id?: number;
    browser_data?: Record<string, any>;
    created_at: string;
    last_seen_at?: string;
}

// Типы для форм
export interface CreateSnippetForm {
    content: string;
    language: string;
    theme: string;
}

// Типы для языков программирования
export type ProgrammingLanguage = 
    | 'php' 
    | 'javascript' 
    | 'python' 
    | 'java' 
    | 'csharp' 
    | 'cpp' 
    | 'go' 
    | 'rust'
    | 'typescript'
    | 'html'
    | 'css'
    | 'sql';

// Типы для тем оформления
export type CodeTheme = 
    | 'vs-dark' 
    | 'vs-light' 
    | 'monokai' 
    | 'github'
    | 'dracula'
    | 'solarized-dark'
    | 'solarized-light'; 