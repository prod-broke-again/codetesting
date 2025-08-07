<?php

namespace Database\Factories;

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
                'php', 'javascript', 'python', 'java', 'csharp', 'cpp', 'go', 'rust', 'typescript',
                'html', 'css', 'sql', 'php-html', 'vue', 'blade', 'jsx', 'tsx', 'html-css',
                'html-js', 'php-blade', 'ruby', 'swift', 'kotlin', 'scala', 'dart', 'elixir',
                'haskell', 'clojure', 'bash', 'powershell', 'yaml', 'json', 'xml', 'markdown'
            ]),
            'privacy' => fake()->randomElement(['public', 'private']),
            'is_guest' => false,
            'edit_token' => fake()->sha256(),
            'user_id' => \App\Models\User::factory(),
            'access_count' => fake()->numberBetween(0, 100),
            'last_accessed_at' => fake()->dateTimeThisMonth(),
        ];
    }
}
