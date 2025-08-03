# Настройка OAuth для CodeTesting.ru

## Google OAuth

### 1. Создание проекта в Google Cloud Console

1. Перейдите в [Google Cloud Console](https://console.cloud.google.com/)
2. Создайте новый проект или выберите существующий
3. Включите Google+ API

### 2. Настройка OAuth 2.0

1. Перейдите в "APIs & Services" > "Credentials"
2. Нажмите "Create Credentials" > "OAuth 2.0 Client IDs"
3. Выберите "Web application"
4. Настройте:
   - **Authorized JavaScript origins:**
     - `http://localhost:8000` (для разработки)
     - `https://codetesting.ru` (для продакшена)
   - **Authorized redirect URIs:**
     - `http://localhost:8000/auth/google/callback` (для разработки)
     - `https://codetesting.ru/auth/google/callback` (для продакшена)

### 3. Получение токенов

После создания вы получите:
- **Client ID** (например: `123456789-abcdef.apps.googleusercontent.com`)
- **Client Secret** (например: `GOCSPX-abcdefghijklmnop`)

## GitHub OAuth

### 1. Создание OAuth App в GitHub

1. Перейдите в [GitHub Settings > Developer settings > OAuth Apps](https://github.com/settings/developers)
2. Нажмите "New OAuth App"
3. Заполните форму:
   - **Application name:** CodeTesting.ru
   - **Homepage URL:** `https://codetesting.ru`
   - **Authorization callback URL:** `https://codetesting.ru/auth/github/callback`

### 2. Получение токенов

После создания вы получите:
- **Client ID** (например: `abcdef123456`)
- **Client Secret** (например: `abcdef1234567890abcdef1234567890abcdef12`)

## Настройка .env файла

Добавьте следующие переменные в ваш `.env` файл:

```env
# Google OAuth
GOOGLE_CLIENT_ID=ваш_google_client_id
GOOGLE_CLIENT_SECRET=ваш_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# GitHub OAuth
GITHUB_CLIENT_ID=ваш_github_client_id
GITHUB_CLIENT_SECRET=ваш_github_client_secret
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

## Для продакшена

При деплое на продакшен измените redirect URIs на:

```env
GOOGLE_REDIRECT_URI=https://codetesting.ru/auth/google/callback
GITHUB_REDIRECT_URI=https://codetesting.ru/auth/github/callback
```

И обновите настройки в Google Cloud Console и GitHub OAuth App соответственно.

## Проверка работы

1. Запустите сервер: `php artisan serve`
2. Перейдите на `http://localhost:8000/auth/login`
3. Попробуйте войти через Google или GitHub

## Безопасность

- Никогда не коммитьте `.env` файл в Git
- Используйте разные токены для разработки и продакшена
- Регулярно обновляйте Client Secret
- Настройте HTTPS для продакшена 