<?php

namespace App\Events;

use App\Models\Code;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CodeAccessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Code $code,
        public readonly ?User $user = null,
        public readonly string $ipAddress = '',
        public readonly string $userAgent = ''
    ) {}
}
