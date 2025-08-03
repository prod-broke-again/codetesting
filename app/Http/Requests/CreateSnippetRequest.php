<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSnippetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Гостевой доступ разрешен
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:50000'], // 50KB максимум
            'language' => ['required', 'string', Rule::in([
                'php', 'javascript', 'python', 'java', 'csharp', 'cpp', 'go', 'rust', 'typescript', 'html', 'css', 'sql',
                'php-html', 'vue', 'blade', 'jsx', 'tsx', 'html-css', 'html-js', 'php-blade',
                'ruby', 'swift', 'kotlin', 'scala', 'dart', 'elixir', 'haskell', 'clojure', 'bash', 'powershell', 'yaml', 'json', 'xml', 'markdown'
            ])],
            'theme' => ['required', 'string', Rule::in([
                'vs-dark', 'vs-light', 'monokai', 'github', 'dracula', 'solarized-dark', 'solarized-light',
                'nord', 'material', 'one-dark', 'one-light', 'tokyo-night', 'catppuccin'
            ])],
            'is_encrypted' => ['sometimes', 'boolean'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:now'],
            'privacy' => ['sometimes', 'string', Rule::in(['private', 'unlisted', 'public'])],
            'password' => ['sometimes', 'nullable', 'string', 'min:4', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Код обязателен для заполнения',
            'content.max' => 'Код не может превышать 50KB',
            'language.required' => 'Язык программирования обязателен',
            'language.in' => 'Выбран неподдерживаемый язык программирования',
            'theme.required' => 'Тема оформления обязательна',
            'theme.in' => 'Выбрана неподдерживаемая тема',
            'expires_at.after' => 'Время истечения должно быть в будущем',
            'privacy.in' => 'Выбран неподдерживаемый тип приватности',
            'password.min' => 'Пароль должен содержать минимум 4 символа',
            'password.max' => 'Пароль не может превышать 255 символов',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'content' => 'код',
            'language' => 'язык программирования',
            'theme' => 'тема оформления',
            'is_encrypted' => 'шифрование',
            'expires_at' => 'время истечения',
            'privacy' => 'приватность',
            'password' => 'пароль',
        ];
    }
}
