<?php

namespace App\Listeners;

use App\Events\CodeCreated;
use App\Events\CodeAccessed;
use Illuminate\Support\Facades\Log;

class CodeAuditListener
{
    /**
     * Обработка события создания кода
     */
    public function handleCodeCreated(CodeCreated $event): void
    {
        Log::info('Code snippet created', [
            'code_id' => $event->code->id,
            'hash' => $event->code->hash,
            'user_id' => $event->user?->id,
            'is_guest' => $event->code->is_guest,
            'language' => $event->code->language,
            'created_at' => $event->code->created_at,
        ]);
    }

    /**
     * Обработка события доступа к коду
     */
    public function handleCodeAccessed(CodeAccessed $event): void
    {
        Log::info('Code snippet accessed', [
            'code_id' => $event->code->id,
            'hash' => $event->code->hash,
            'user_id' => $event->user?->id,
            'ip_address' => $event->ipAddress,
            'user_agent' => $event->userAgent,
            'access_count' => $event->code->access_count,
            'accessed_at' => now(),
        ]);
    }
}
