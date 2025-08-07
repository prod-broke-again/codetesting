<?php

namespace App\Providers;

use App\Contracts\CodeRepositoryInterface;
use App\Contracts\SnippetSearchServiceInterface;
use App\Repositories\CodeRepository;
use App\Services\SnippetSearchService;
use App\Events\CodeCreated;
use App\Events\CodeAccessed;
use App\Listeners\CodeAuditListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Регистрация репозиториев
        $this->app->bind(CodeRepositoryInterface::class, CodeRepository::class);
        
        // Регистрация сервисов
        $this->app->bind(SnippetSearchServiceInterface::class, SnippetSearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрация слушателей событий
        Event::listen(CodeCreated::class, [CodeAuditListener::class, 'handleCodeCreated']);
        Event::listen(CodeAccessed::class, [CodeAuditListener::class, 'handleCodeAccessed']);
    }
}
