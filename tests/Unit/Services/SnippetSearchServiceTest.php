<?php

namespace Tests\Unit\Services;

use App\Services\SnippetSearchService;
use App\Models\Code;
use App\Models\User;
use App\Enums\ProgrammingLanguage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SnippetSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    private SnippetSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SnippetSearchService();
    }

    /** @test */
    public function it_can_search_public_snippets()
    {
        // Создаем тестового пользователя
        $user = User::factory()->create();

        // Создаем публичный сниппет
        Code::factory()->create([
            'user_id' => $user->id,
            'privacy' => 'public',
            'is_guest' => false,
            'language' => 'php',
            'content' => 'test content'
        ]);

        // Создаем приватный сниппет (не должен попасть в результаты)
        Code::factory()->create([
            'user_id' => $user->id,
            'privacy' => 'private',
            'is_guest' => false,
            'language' => 'javascript',
            'content' => 'private content'
        ]);

        $filters = ['language' => 'php'];
        $result = $this->service->searchPublicSnippets($filters, 10);

        $this->assertEquals(1, $result->total());
        $this->assertEquals(ProgrammingLanguage::PHP, $result->items()[0]->language);
    }

    /** @test */
    public function it_can_get_available_languages()
    {
        // Создаем тестового пользователя
        $user = User::factory()->create();

        // Создаем сниппеты с разными языками
        Code::factory()->create([
            'user_id' => $user->id,
            'privacy' => 'public',
            'is_guest' => false,
            'language' => 'php'
        ]);

        Code::factory()->create([
            'user_id' => $user->id,
            'privacy' => 'public',
            'is_guest' => false,
            'language' => 'javascript'
        ]);

        $languages = $this->service->getAvailableLanguages();

        $this->assertIsArray($languages);
        $this->assertContains(ProgrammingLanguage::PHP, $languages);
        $this->assertContains(ProgrammingLanguage::JAVASCRIPT, $languages);
    }

    /** @test */
    public function it_can_get_statistics()
    {
        // Создаем тестового пользователя
        $user = User::factory()->create();

        // Создаем несколько сниппетов
        Code::factory()->create([
            'user_id' => $user->id,
            'privacy' => 'public',
            'language' => 'php'
        ]);

        Code::factory()->create([
            'user_id' => $user->id,
            'privacy' => 'private',
            'language' => 'javascript'
        ]);

        $stats = $this->service->getStatistics();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_snippets', $stats);
        $this->assertArrayHasKey('public_snippets', $stats);
        $this->assertArrayHasKey('private_snippets', $stats);
        $this->assertArrayHasKey('top_languages', $stats);
        $this->assertArrayHasKey('weekly_activity', $stats);

        $this->assertEquals(2, $stats['total_snippets']);
        $this->assertEquals(1, $stats['public_snippets']);
        $this->assertEquals(1, $stats['private_snippets']);
    }

    /** @test */
    public function it_supports_all_programming_languages()
    {
        // Создаем тестового пользователя
        $user = User::factory()->create();

        // Создаем сниппеты с разными языками
        $languages = [
            'php', 'javascript', 'python', 'java', 'csharp', 'cpp', 'go', 'rust', 'typescript',
            'html', 'css', 'sql', 'php-html', 'vue', 'blade', 'jsx', 'tsx', 'html-css',
            'html-js', 'php-blade', 'ruby', 'swift', 'kotlin', 'scala', 'dart', 'elixir',
            'haskell', 'clojure', 'bash', 'powershell', 'yaml', 'json', 'xml', 'markdown'
        ];

        foreach ($languages as $language) {
            Code::factory()->create([
                'user_id' => $user->id,
                'privacy' => 'public',
                'language' => $language
            ]);
        }

        $availableLanguages = $this->service->getAvailableLanguages();
        
        // Проверяем, что все языки поддерживаются
        foreach ($languages as $language) {
            $languageEnum = ProgrammingLanguage::from($language);
            $this->assertContains($languageEnum, $availableLanguages);
        }
    }
}
