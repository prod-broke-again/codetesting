<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\DeleteAccountRequest;
use App\Services\UserProfileService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        try {
            $this->requireAuth();
            $user = $request->user();

            return Inertia::render('Profile', [
                'user' => $user,
                'stats' => $this->profileService->getUserStats($user),
                'recentSnippets' => $this->profileService->getRecentSnippets($user),
                'title' => 'Профиль',
                'description' => 'Управление профилем'
            ]);
        } catch (\Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Profile page load failed',
                extraData: ['user_id' => $request->user()?->id]
            );
        }
    }

    /**
     * Обновить профиль
     */
    public function update(UpdateProfileRequest $request)
    {
        try {
            $this->requireAuth();
            $user = $request->user();

            $this->profileService->updateProfile($user, $request->validated());
            
            return back()->with('message', 'Профиль успешно обновлен');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Profile update failed',
                extraData: ['user_id' => $request->user()?->id]
            );
        }
    }

    /**
     * Удалить аккаунт
     */
    public function destroy(DeleteAccountRequest $request)
    {
        try {
            $this->requireAuth();
            $user = $request->user();

            $this->profileService->deleteAccount($user, $request->validated()['password']);
            
            return redirect('/')->with('message', 'Аккаунт успешно удален');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Account deletion failed',
                extraData: ['user_id' => $request->user()?->id]
            );
        }
    }
}
