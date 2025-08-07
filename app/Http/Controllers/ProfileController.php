<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\DeleteAccountRequest;
use App\Services\UserProfileService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __construct(
        private UserProfileService $profileService
    ) {}

    /**
     * Показать страницу профиля
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        return Inertia::render('Profile', [
            'user' => $user,
            'stats' => $this->profileService->getUserStats($user),
            'recentSnippets' => $this->profileService->getRecentSnippets($user),
            'title' => 'Профиль',
            'description' => 'Управление профилем'
        ]);
    }

    /**
     * Обновить профиль
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        try {
            $this->profileService->updateProfile($user, $request->validated());
            return back()->with('message', 'Профиль успешно обновлен');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Удалить аккаунт
     */
    public function destroy(DeleteAccountRequest $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        try {
            $this->profileService->deleteAccount($user, $request->validated()['password']);
            return redirect('/')->with('message', 'Аккаунт успешно удален');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}
