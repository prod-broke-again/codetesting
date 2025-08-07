<?php

namespace Database\Factories;

use App\Enums\ProgrammingLanguage;
use App\Enums\SnippetPrivacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Code>
 */
class CodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hash' => fake()->unique()->sha256(),
            'title' => fake()->sentence(3),
            'content' => fake()->text(500),
            'language' => fake()->randomElement([
                ProgrammingLanguage::PHP,
                ProgrammingLanguage::JAVASCRIPT,
                ProgrammingLanguage::PYTHON,
                ProgrammingLanguage::JAVA,
                ProgrammingLanguage::CSHARP,
                ProgrammingLanguage::CPP,
                ProgrammingLanguage::GO,
                ProgrammingLanguage::RUST,
                ProgrammingLanguage::TYPESCRIPT,
                ProgrammingLanguage::HTML,
                ProgrammingLanguage::CSS,
                ProgrammingLanguage::SQL,
                ProgrammingLanguage::PHP_HTML,
                ProgrammingLanguage::VUE,
                ProgrammingLanguage::BLADE,
                ProgrammingLanguage::JSX,
                ProgrammingLanguage::TSX,
                ProgrammingLanguage::HTML_CSS,
                ProgrammingLanguage::HTML_JS,
                ProgrammingLanguage::PHP_BLADE,
                ProgrammingLanguage::RUBY,
                ProgrammingLanguage::SWIFT,
                ProgrammingLanguage::KOTLIN,
                ProgrammingLanguage::SCALA,
                ProgrammingLanguage::DART,
                ProgrammingLanguage::ELIXIR,
                ProgrammingLanguage::HASKELL,
                ProgrammingLanguage::CLOJURE,
                ProgrammingLanguage::BASH,
                ProgrammingLanguage::POWERSHELL,
                ProgrammingLanguage::YAML,
                ProgrammingLanguage::JSON,
                ProgrammingLanguage::XML,
                ProgrammingLanguage::MARKDOWN
            ]),
            'privacy' => fake()->randomElement([
                SnippetPrivacy::PUBLIC,
                SnippetPrivacy::PRIVATE,
                SnippetPrivacy::UNLISTED
            ]),
            'is_guest' => false,
            'edit_token' => fake()->sha256(),
            'user_id' => User::factory(),
            'access_count' => fake()->numberBetween(0, 100),
            'last_accessed_at' => fake()->dateTimeThisMonth(),
        ];
    }
}
