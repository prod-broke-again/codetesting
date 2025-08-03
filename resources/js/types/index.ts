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
    language: ProgrammingLanguage;
    theme: CodeTheme;
    is_encrypted?: boolean;
    expires_at?: string;
}

// Типы для языков программирования
export type ProgrammingLanguage = 
    // Основные языки
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
    | 'sql'
    // Смешанные типы
    | 'php-html'
    | 'vue'
    | 'blade'
    | 'jsx'
    | 'tsx'
    | 'html-css'
    | 'html-js'
    | 'php-blade'
    // Дополнительные языки
    | 'ruby'
    | 'swift'
    | 'kotlin'
    | 'scala'
    | 'dart'
    | 'elixir'
    | 'haskell'
    | 'clojure'
    | 'bash'
    | 'powershell'
    | 'yaml'
    | 'json'
    | 'xml'
    | 'markdown';

// Типы для тем оформления
export type CodeTheme = 
    | 'vs-dark' 
    | 'vs-light' 
    | 'monokai' 
    | 'github'
    | 'dracula'
    | 'solarized-dark'
    | 'solarized-light'
    | 'nord'
    | 'material'
    | 'one-dark'
    | 'one-light'
    | 'tokyo-night'
    | 'catppuccin';

// Константы для языков с описаниями
export const LANGUAGE_OPTIONS: Record<ProgrammingLanguage, string> = {
    // Основные языки
    'php': 'PHP',
    'javascript': 'JavaScript',
    'python': 'Python',
    'java': 'Java',
    'csharp': 'C#',
    'cpp': 'C++',
    'go': 'Go',
    'rust': 'Rust',
    'typescript': 'TypeScript',
    'html': 'HTML',
    'css': 'CSS',
    'sql': 'SQL',
    // Смешанные типы
    'php-html': 'PHP + HTML',
    'vue': 'Vue.js',
    'blade': 'Laravel Blade',
    'jsx': 'JSX (React)',
    'tsx': 'TSX (React + TypeScript)',
    'html-css': 'HTML + CSS',
    'html-js': 'HTML + JavaScript',
    'php-blade': 'PHP + Blade',
    // Дополнительные языки
    'ruby': 'Ruby',
    'swift': 'Swift',
    'kotlin': 'Kotlin',
    'scala': 'Scala',
    'dart': 'Dart',
    'elixir': 'Elixir',
    'haskell': 'Haskell',
    'clojure': 'Clojure',
    'bash': 'Bash',
    'powershell': 'PowerShell',
    'yaml': 'YAML',
    'json': 'JSON',
    'xml': 'XML',
    'markdown': 'Markdown'
};

// Константы для тем с описаниями
export const THEME_OPTIONS: Record<CodeTheme, string> = {
    'vs-dark': 'VS Code Dark',
    'vs-light': 'VS Code Light',
    'monokai': 'Monokai',
    'github': 'GitHub',
    'dracula': 'Dracula',
    'solarized-dark': 'Solarized Dark',
    'solarized-light': 'Solarized Light',
    'nord': 'Nord',
    'material': 'Material',
    'one-dark': 'One Dark',
    'one-light': 'One Light',
    'tokyo-night': 'Tokyo Night',
    'catppuccin': 'Catppuccin'
}; 