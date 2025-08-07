<?php

namespace App\Services;

use App\Models\Code;
use App\Models\User;
use Illuminate\Support\Collection;
use ZipArchive;

class SnippetExportService
{
    /**
     * Экспортировать сниппеты пользователя в JSON формате
     */
    public function exportToJson(User $user): array
    {
        $snippets = Code::where('user_id', $user->id)
            ->select([
                'title', 'content', 'language', 'privacy', 
                'created_at', 'updated_at', 'access_count'
            ])
            ->get()
            ->toArray();

        return [
            'export_date' => now()->toISOString(),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'total_snippets' => count($snippets),
            'snippets' => $snippets,
        ];
    }

    /**
     * Экспортировать сниппеты пользователя в ZIP архив с отдельными файлами
     */
    public function exportToZip(User $user): ?string
    {
        $snippets = Code::where('user_id', $user->id)->get();
        
        if ($snippets->isEmpty()) {
            return null;
        }

        $zipFileName = storage_path('app/temp/' . $user->id . '_snippets_' . time() . '.zip');
        
        // Создаем директорию если её нет
        if (!file_exists(dirname($zipFileName))) {
            mkdir(dirname($zipFileName), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
            return null;
        }

        foreach ($snippets as $snippet) {
            $fileExtension = $this->getFileExtensionForLanguage($snippet->language);
            $fileName = $this->sanitizeFileName($snippet->title ?: 'snippet_' . $snippet->id) . $fileExtension;
            
            $fileContent = $this->generateFileHeader($snippet) . $snippet->content;
            $zip->addFromString($fileName, $fileContent);
        }

        // Добавляем README с информацией об экспорте
        $readme = $this->generateReadme($user, $snippets);
        $zip->addFromString('README.txt', $readme);

        $zip->close();

        return $zipFileName;
    }

    /**
     * Получить расширение файла для языка программирования
     */
    private function getFileExtensionForLanguage(string $language): string
    {
        $extensions = [
            'php' => '.php',
            'javascript' => '.js',
            'typescript' => '.ts',
            'python' => '.py',
            'java' => '.java',
            'csharp' => '.cs',
            'cpp' => '.cpp',
            'c' => '.c',
            'go' => '.go',
            'rust' => '.rs',
            'ruby' => '.rb',
            'swift' => '.swift',
            'kotlin' => '.kt',
            'scala' => '.scala',
            'dart' => '.dart',
            'html' => '.html',
            'css' => '.css',
            'sql' => '.sql',
            'bash' => '.sh',
            'powershell' => '.ps1',
            'yaml' => '.yml',
            'json' => '.json',
            'xml' => '.xml',
            'markdown' => '.md',
            'vue' => '.vue',
            'jsx' => '.jsx',
            'tsx' => '.tsx',
        ];

        return $extensions[$language] ?? '.txt';
    }

    /**
     * Очистить имя файла от недопустимых символов
     */
    private function sanitizeFileName(string $filename): string
    {
        // Убираем недопустимые символы
        $filename = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $filename);
        // Заменяем пробелы на подчеркивания
        $filename = str_replace(' ', '_', $filename);
        // Ограничиваем длину
        return substr($filename, 0, 50);
    }

    /**
     * Сгенерировать заголовок для файла
     */
    private function generateFileHeader(Code $snippet): string
    {
        $header = "/*\n";
        $header .= " * Title: " . ($snippet->title ?: 'Untitled') . "\n";
        $header .= " * Language: " . $snippet->language . "\n";
        $header .= " * Privacy: " . $snippet->privacy . "\n";
        $header .= " * Created: " . $snippet->created_at->format('Y-m-d H:i:s') . "\n";
        $header .= " * Last Updated: " . $snippet->updated_at->format('Y-m-d H:i:s') . "\n";
        $header .= " * Access Count: " . $snippet->access_count . "\n";
        $header .= " * Exported from CodeTesting.ru\n";
        $header .= " */\n\n";

        return $header;
    }

    /**
     * Сгенерировать README файл
     */
    private function generateReadme(User $user, Collection $snippets): string
    {
        $readme = "# CodeTesting.ru - Экспорт сниппетов\n\n";
        $readme .= "Пользователь: {$user->name} ({$user->email})\n";
        $readme .= "Дата экспорта: " . now()->format('Y-m-d H:i:s') . "\n";
        $readme .= "Всего сниппетов: " . $snippets->count() . "\n\n";

        $readme .= "## Статистика по языкам:\n";
        $languageStats = $snippets->groupBy('language')->map->count();
        foreach ($languageStats as $language => $count) {
            $readme .= "- {$language}: {$count} сниппет(ов)\n";
        }

        $readme .= "\n## Статистика по приватности:\n";
        $privacyStats = $snippets->groupBy('privacy')->map->count();
        foreach ($privacyStats as $privacy => $count) {
            $readme .= "- {$privacy}: {$count} сниппет(ов)\n";
        }

        $readme .= "\n---\n";
        $readme .= "Экспортировано с платформы CodeTesting.ru\n";
        $readme .= "https://codetesting.ru\n";

        return $readme;
    }

    /**
     * Удалить временный файл экспорта
     */
    public function cleanupTempFile(string $filePath): bool
    {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }
}
