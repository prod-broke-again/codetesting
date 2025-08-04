<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Показать страницу профиля
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        // Статистика пользователя
        $stats = [
            'total_snippets' => Code::where('user_id', $user->id)->count(),
            'public_snippets' => Code::where('user_id', $user->id)->where('privacy', 'public')->count(),
            'private_snippets' => Code::where('user_id', $user->id)->where('privacy', 'private')->count(),
            'unlisted_snippets' => Code::where('user_id', $user->id)->where('privacy', 'unlisted')->count(),
            'total_views' => Code::where('user_id', $user->id)->sum('access_count'),
            'encrypted_snippets' => Code::where('user_id', $user->id)->where('is_encrypted', true)->count(),
        ];

        // Последние сниппеты
        $recentSnippets = Code::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Profile', [
            'user' => $user,
            'stats' => $stats,
            'recentSnippets' => $recentSnippets,
            'title' => 'Профиль',
            'description' => 'Управление профилем'
        ]);
    }

    /**
     * Обновить профиль
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8'],
            'new_password_confirmation' => ['nullable', 'string', 'same:new_password'],
        ]);

        // Обновляем основную информацию
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Обновляем пароль если указан
        if ($validated['new_password']) {
            if (!$user->password || Hash::check($validated['current_password'], $user->password)) {
                $user->update([
                    'password' => Hash::make($validated['new_password'])
                ]);
            } else {
                return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
            }
        }

        return back()->with('message', 'Профиль успешно обновлен');
    }

    /**
     * Удалить аккаунт
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (Hash::check($validated['password'], $user->password)) {
            // Удаляем все сниппеты пользователя
            Code::where('user_id', $user->id)->delete();
            
            // Удаляем пользователя
            $user->delete();
            
            return redirect('/')->with('message', 'Аккаунт успешно удален');
        }

        return back()->withErrors(['password' => 'Неверный пароль']);
    }
}
