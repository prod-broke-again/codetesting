<?php

namespace App\Enums;

enum ProgrammingLanguage: string
{
    // Основные языки
    case PHP = 'php';
    case JAVASCRIPT = 'javascript';
    case PYTHON = 'python';
    case JAVA = 'java';
    case CSHARP = 'csharp';
    case CPP = 'cpp';
    case GO = 'go';
    case RUST = 'rust';
    case TYPESCRIPT = 'typescript';
    case HTML = 'html';
    case CSS = 'css';
    case SQL = 'sql';
    
    // Смешанные типы
    case PHP_HTML = 'php-html';
    case VUE = 'vue';
    case BLADE = 'blade';
    case JSX = 'jsx';
    case TSX = 'tsx';
    case HTML_CSS = 'html-css';
    case HTML_JS = 'html-js';
    case PHP_BLADE = 'php-blade';
    
    // Дополнительные языки
    case RUBY = 'ruby';
    case SWIFT = 'swift';
    case KOTLIN = 'kotlin';
    case SCALA = 'scala';
    case DART = 'dart';
    case ELIXIR = 'elixir';
    case HASKELL = 'haskell';
    case CLOJURE = 'clojure';
    case BASH = 'bash';
    case POWERSHELL = 'powershell';
    case YAML = 'yaml';
    case JSON = 'json';
    case XML = 'xml';
    case MARKDOWN = 'markdown';

    public function label(): string
    {
        return match($this) {
            // Основные языки
            self::PHP => 'PHP',
            self::JAVASCRIPT => 'JavaScript',
            self::PYTHON => 'Python',
            self::JAVA => 'Java',
            self::CSHARP => 'C#',
            self::CPP => 'C++',
            self::GO => 'Go',
            self::RUST => 'Rust',
            self::TYPESCRIPT => 'TypeScript',
            self::HTML => 'HTML',
            self::CSS => 'CSS',
            self::SQL => 'SQL',
            
            // Смешанные типы
            self::PHP_HTML => 'PHP + HTML',
            self::VUE => 'Vue.js',
            self::BLADE => 'Laravel Blade',
            self::JSX => 'JSX (React)',
            self::TSX => 'TSX (React + TypeScript)',
            self::HTML_CSS => 'HTML + CSS',
            self::HTML_JS => 'HTML + JavaScript',
            self::PHP_BLADE => 'PHP + Blade',
            
            // Дополнительные языки
            self::RUBY => 'Ruby',
            self::SWIFT => 'Swift',
            self::KOTLIN => 'Kotlin',
            self::SCALA => 'Scala',
            self::DART => 'Dart',
            self::ELIXIR => 'Elixir',
            self::HASKELL => 'Haskell',
            self::CLOJURE => 'Clojure',
            self::BASH => 'Bash',
            self::POWERSHELL => 'PowerShell',
            self::YAML => 'YAML',
            self::JSON => 'JSON',
            self::XML => 'XML',
            self::MARKDOWN => 'Markdown',
        };
    }

    public function extension(): string
    {
        return match($this) {
            // Основные языки
            self::PHP => 'php',
            self::JAVASCRIPT => 'js',
            self::PYTHON => 'py',
            self::JAVA => 'java',
            self::CSHARP => 'cs',
            self::CPP => 'cpp',
            self::GO => 'go',
            self::RUST => 'rs',
            self::TYPESCRIPT => 'ts',
            self::HTML => 'html',
            self::CSS => 'css',
            self::SQL => 'sql',
            
            // Смешанные типы
            self::PHP_HTML => 'php',
            self::VUE => 'vue',
            self::BLADE => 'blade.php',
            self::JSX => 'jsx',
            self::TSX => 'tsx',
            self::HTML_CSS => 'html',
            self::HTML_JS => 'html',
            self::PHP_BLADE => 'blade.php',
            
            // Дополнительные языки
            self::RUBY => 'rb',
            self::SWIFT => 'swift',
            self::KOTLIN => 'kt',
            self::SCALA => 'scala',
            self::DART => 'dart',
            self::ELIXIR => 'ex',
            self::HASKELL => 'hs',
            self::CLOJURE => 'clj',
            self::BASH => 'sh',
            self::POWERSHELL => 'ps1',
            self::YAML => 'yml',
            self::JSON => 'json',
            self::XML => 'xml',
            self::MARKDOWN => 'md',
        };
    }
}
