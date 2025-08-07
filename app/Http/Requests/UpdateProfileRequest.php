<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user()->id)],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8'],
            'new_password_confirmation' => ['nullable', 'string', 'same:new_password'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно для заполнения',
            'name.max' => 'Имя не может превышать 255 символов',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Email должен быть корректным',
            'email.unique' => 'Этот email уже используется',
            'new_password.min' => 'Новый пароль должен содержать минимум 8 символов',
            'new_password_confirmation.same' => 'Пароли не совпадают',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'имя',
            'email' => 'email',
            'current_password' => 'текущий пароль',
            'new_password' => 'новый пароль',
            'new_password_confirmation' => 'подтверждение пароля',
        ];
    }
}
