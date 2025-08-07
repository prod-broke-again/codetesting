<?php

namespace Tests\Feature;

use App\Enums\ProgrammingLanguage;
use App\Enums\SnippetPrivacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SnippetCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_public_snippet()
    {
        $response = $this->postJson('/api/snippets', [
            'content' => 'console.log("Hello World");',
            'language' => ProgrammingLanguage::JAVASCRIPT->value,
            'privacy' => SnippetPrivacy::PUBLIC->value,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'hash',
                    'content',
                    'language',
                    'is_guest',
                    'edit_token',
                    'url'
                ]
            ]);

        $this->assertDatabaseHas('codes', [
            'content' => 'console.log("Hello World");',
            'language' => ProgrammingLanguage::JAVASCRIPT->value,
            'privacy' => SnippetPrivacy::PUBLIC->value,
            'is_guest' => true,
        ]);
    }

    public function test_guest_cannot_create_encrypted_snippet()
    {
        $response = $this->postJson('/api/snippets', [
            'content' => 'secret code',
            'language' => ProgrammingLanguage::PHP->value,
            'is_encrypted' => true,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_encrypted']);
    }

    public function test_authenticated_user_can_create_encrypted_snippet()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/snippets', [
                'content' => '<?php echo "secret"; ?>',
                'language' => ProgrammingLanguage::PHP->value,
                'is_encrypted' => true,
                'privacy' => SnippetPrivacy::PRIVATE->value,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('codes', [
            'user_id' => $user->id,
            'is_encrypted' => true,
            'privacy' => SnippetPrivacy::PRIVATE->value,
            'is_guest' => false,
        ]);
    }

    public function test_snippet_requires_valid_language()
    {
        $response = $this->postJson('/api/snippets', [
            'content' => 'some code',
            'language' => 'invalid-language',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language']);
    }

    public function test_snippet_requires_content()
    {
        $response = $this->postJson('/api/snippets', [
            'language' => ProgrammingLanguage::PYTHON->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    public function test_snippet_content_has_max_length()
    {
        $longContent = str_repeat('a', 10001); // Больше лимита в 10000

        $response = $this->postJson('/api/snippets', [
            'content' => $longContent,
            'language' => ProgrammingLanguage::PYTHON->value,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    public function test_guest_snippet_gets_edit_token()
    {
        $response = $this->postJson('/api/snippets', [
            'content' => 'print("Hello")',
            'language' => ProgrammingLanguage::PYTHON->value,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.is_guest', true)
            ->assertJsonStructure([
                'data' => [
                    'edit_token'
                ]
            ]);

        $snippetData = $response->json('data');
        $this->assertStringStartsWith('tk_', $snippetData['edit_token']);
    }

    public function test_authenticated_user_snippet_does_not_get_edit_token()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/snippets', [
                'content' => 'print("Hello")',
                'language' => ProgrammingLanguage::PYTHON->value,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.is_guest', false)
            ->assertJsonMissing([
                'data' => [
                    'edit_token'
                ]
            ]);
    }
} 