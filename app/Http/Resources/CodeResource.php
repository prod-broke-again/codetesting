<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class CodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'hash' => $this->hash,
            'language' => $this->language,
            'theme' => $this->theme,
            'is_encrypted' => $this->is_encrypted,
            'is_guest' => $this->is_guest,
            'access_count' => $this->access_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expires_at' => $this->expires_at,
            'last_accessed_at' => $this->last_accessed_at,
        ];

        // Добавляем контент (расшифрованный если нужно)
        if ($this->is_encrypted) {
            try {
                $data['content'] = Crypt::decryptString($this->content);
            } catch (Exception $e) {
                $data['content'] = 'Ошибка расшифровки контента';
            }
        } else {
            $data['content'] = $this->content;
        }

        // Добавляем токен только при создании
        if ($this->wasRecentlyCreated && $this->is_guest) {
            $data['edit_token'] = $this->edit_token;
            $data['url'] = url("/code/{$this->hash}");
        }

        // Добавляем информацию о владельце только для авторизованных пользователей
        if ($request->user() && $this->user_id === $request->user()->id) {
            $data['user_id'] = $this->user_id;
        }

        return $data;
    }
}
