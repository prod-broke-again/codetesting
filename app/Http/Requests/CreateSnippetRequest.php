<?php

namespace App\Http\Requests;

use App\Enums\ProgrammingLanguage;
use App\Enums\SnippetPrivacy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateSnippetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:10000'],
            'language' => ['required', new Enum(ProgrammingLanguage::class)],
            'privacy' => ['sometimes', new Enum(SnippetPrivacy::class)],
            'is_encrypted' => ['sometimes', 'boolean'],
            'theme' => ['sometimes', 'string', 'in:vs-dark,vs-light,github-dark,github-light'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:now'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Гости не могут создавать зашифрованные сниппеты
            if (!$this->user() && $this->boolean('is_encrypted')) {
                $validator->errors()->add('is_encrypted', 'Гости не могут создавать зашифрованные сниппеты');
            }

            // Гости не могут создавать приватные сниппеты
            if (!$this->user() && $this->input('privacy') === SnippetPrivacy::PRIVATE->value) {
                $validator->errors()->add('privacy', 'Гости не могут создавать приватные сниппеты');
            }
        });
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Код обязателен для заполнения',
            'content.max' => 'Код не может превышать 10000 символов',
            'language.required' => 'Язык программирования обязателен',
            'language.enum' => 'Неподдерживаемый язык программирования',
            'privacy.enum' => 'Неподдерживаемый тип приватности',
            'theme.in' => 'Неподдерживаемая тема',
            'expires_at.after' => 'Дата истечения должна быть в будущем',
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => 'код',
            'language' => 'язык программирования',
            'privacy' => 'приватность',
            'is_encrypted' => 'шифрование',
            'theme' => 'тема',
            'expires_at' => 'дата истечения',
        ];
    }
}
