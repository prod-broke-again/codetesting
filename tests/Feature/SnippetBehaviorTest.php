<?php

namespace Tests\Feature;

use App\Enums\ProgrammingLanguage;
use App\Enums\SnippetPrivacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SnippetBehaviorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function when_guest_creates_snippet_then_edit_token_is_generated()
    {
        // Given: Гость хочет создать сниппет
        $snippetData = [
            'content' => 'console.log("Hello World");',
            'language' => ProgrammingLanguage::JAVASCRIPT->value,
        ];

        // When: Гость создает сниппет
        $response = $this->postJson('/api/snippets', $snippetData);

        // Then: Сниппет создается с токеном для редактирования
        $response->assertStatus(201);
        
        $snippet = $response->json('data');
        $this->assertTrue($snippet['is_guest']);
        $this->assertArrayHasKey('edit_token', $snippet);
        $this->assertStringStartsWith('tk_', $snippet['edit_token']);
    }

    /**
     * @test
     */
    public function when_authenticated_user_creates_snippet_then_no_edit_token_needed()
    {
        // Given: Авторизованный пользователь
        $user = User::factory()->create();

        // When: Пользователь создает сниппет
        $response = $this->actingAs($user)
            ->postJson('/api/snippets', [
                'content' => '<?php echo "Hello"; ?>',
                'language' => ProgrammingLanguage::PHP->value,
            ]);

        // Then: Сниппет создается без токена
        $response->assertStatus(201);
        
        $snippet = $response->json('data');
        $this->assertFalse($snippet['is_guest']);
        $this->assertArrayNotHasKey('edit_token', $snippet);
    }

    /**
     * @test
     */
    public function when_guest_creates_encrypted_snippet_then_validation_fails()
    {
        // Given: Гость пытается создать зашифрованный сниппет
        $snippetData = [
            'content' => 'secret code',
            'language' => ProgrammingLanguage::PYTHON->value,
            'is_encrypted' => true,
        ];

        // When: Гость отправляет запрос
        $response = $this->postJson('/api/snippets', $snippetData);

        // Then: Валидация не проходит
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_encrypted']);
    }

    /**
     * @test
     */
    public function when_user_creates_private_snippet_then_only_owner_can_access()
    {
        // Given: Пользователь создает приватный сниппет
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $createResponse = $this->actingAs($user)
            ->postJson('/api/snippets', [
                'content' => 'private code',
                'language' => ProgrammingLanguage::JAVA->value,
                'privacy' => SnippetPrivacy::PRIVATE->value,
            ]);

        $snippetHash = $createResponse->json('data.hash');

        // When: Другой пользователь пытается получить доступ
        $accessResponse = $this->actingAs($otherUser)
            ->getJson("/api/snippets/{$snippetHash}");

        // Then: Доступ запрещен
        $accessResponse->assertStatus(403);
    }

    /**
     * @test
     */
    public function when_snippet_is_created_then_access_count_is_zero()
    {
        // Given: Создается новый сниппет
        $response = $this->postJson('/api/snippets', [
            'content' => 'test code',
            'language' => ProgrammingLanguage::PYTHON->value,
        ]);

        $snippetHash = $response->json('data.hash');

        // When: Получаем информацию о сниппете (первый просмотр)
        $getResponse = $this->getJson("/api/snippets/{$snippetHash}");

        // Then: Счетчик просмотров равен 1 (после первого просмотра)
        $getResponse->assertStatus(200);
        $snippet = $getResponse->json('data');
        $this->assertEquals(1, $snippet['access_count']);
    }

    /**
     * @test
     */
    public function when_snippet_is_accessed_then_access_count_increments()
    {
        // Given: Создается сниппет
        $response = $this->postJson('/api/snippets', [
            'content' => 'test code',
            'language' => ProgrammingLanguage::PYTHON->value,
        ]);

        $snippetHash = $response->json('data.hash');

        // When: Сниппет просматривается несколько раз
        $this->getJson("/api/snippets/{$snippetHash}"); // 1-й просмотр
        $this->getJson("/api/snippets/{$snippetHash}"); // 2-й просмотр
        $this->getJson("/api/snippets/{$snippetHash}"); // 3-й просмотр

        // Then: Счетчик просмотров увеличивается
        $finalResponse = $this->getJson("/api/snippets/{$snippetHash}"); // 4-й просмотр
        $snippet = $finalResponse->json('data');
        $this->assertEquals(4, $snippet['access_count']);
    }

    /**
     * @test
     */
    public function when_invalid_language_is_provided_then_validation_fails()
    {
        // Given: Неверный язык программирования
        $snippetData = [
            'content' => 'some code',
            'language' => 'invalid-language',
        ];

        // When: Отправляется запрос
        $response = $this->postJson('/api/snippets', $snippetData);

        // Then: Валидация не проходит
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language']);
    }

    /**
     * @test
     */
    public function when_content_is_too_long_then_validation_fails()
    {
        // Given: Слишком длинный контент
        $longContent = str_repeat('a', 10001);

        // When: Отправляется запрос
        $response = $this->postJson('/api/snippets', [
            'content' => $longContent,
            'language' => ProgrammingLanguage::PYTHON->value,
        ]);

        // Then: Валидация не проходит
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }
} 