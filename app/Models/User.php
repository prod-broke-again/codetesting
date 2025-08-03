<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'github_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Получить сниппеты пользователя
     */
    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    /**
     * Получить fingerprints пользователя
     */
    public function fingerprints()
    {
        return $this->hasMany(Fingerprint::class);
    }

    /**
     * Проверить, является ли пользователь гостем
     */
    public function isGuest(): bool
    {
        return false;
    }

    /**
     * Проверить, имеет ли пользователь премиум доступ
     */
    public function hasPremiumAccess(): bool
    {
        // TODO: Реализовать логику премиум доступа
        return false;
    }
}
