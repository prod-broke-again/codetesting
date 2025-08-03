# CodeTesting.ru

Современная платформа для создания и обмена зашифрованными сниппетами кода.

## 🚀 Возможности

- **Создание сниппетов** с поддержкой 30+ языков программирования
- **Смешанные типы кода** - PHP+HTML, Vue.js, Laravel Blade, JSX/TSX
- **Шифрование контента** для обеспечения безопасности
- **Гостевой доступ** без регистрации
- **Система токенов** для редактирования сниппетов
- **Умная система восстановления** - автоматическое связывание сниппетов (опционально)
- **Современный UI** с Vue 3 + TypeScript
- **Адаптивный дизайн** с Tailwind CSS

## 🛠️ Технологии

### Backend
- **Laravel 12** - современный PHP фреймворк
- **PHP 8.2+** - последняя версия PHP
- **MySQL/PostgreSQL** - база данных
- **Spatie Crypto** - шифрование данных
- **Laravel Sanctum** - API аутентификация

### Frontend
- **Vue 3** - прогрессивный JavaScript фреймворк
- **TypeScript** - типизированный JavaScript
- **Tailwind CSS** - utility-first CSS фреймворк
- **Vue Router** - клиентская маршрутизация
- **Pinia** - управление состоянием

## 📦 Установка

### Требования
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL

### Backend
```bash
# Клонирование репозитория
git clone git@github.com:prod-broke-again/codetesting.git
cd codetesting

# Установка зависимостей
composer install

# Настройка окружения
cp .env.example .env
php artisan key:generate

# Настройка базы данных
php artisan migrate

# Запуск сервера
php artisan serve
```

### Frontend
```bash
# Установка зависимостей
npm install

# Запуск в режиме разработки
npm run dev

# Сборка для продакшена
npm run build
```

## 🏗️ Архитектура

### Backend (Laravel)
- **Domain-Driven Design** - организация по доменам
- **Repository Pattern** - абстракция доступа к данным
- **Service Layer** - бизнес-логика в сервисах
- **API-First** - RESTful API для фронтенда

### Frontend (Vue 3)
- **Composition API** - современный подход к логике
- **TypeScript** - строгая типизация
- **Component Architecture** - модульная архитектура
- **State Management** - централизованное управление состоянием

## 🔐 Безопасность

- **Шифрование контента** - все сниппеты зашифрованы
- **CSRF защита** - защита от межсайтовых запросов
- **Валидация данных** - проверка всех входных данных
- **Rate Limiting** - ограничение частоты запросов
- **Умная система восстановления** - помогает найти ваши сниппеты (без личных данных)

## 📊 API Endpoints

### Сниппеты
- `POST /api/codes` - создание сниппета
- `GET /api/codes/{hash}` - просмотр сниппета
- `PUT /api/codes/{hash}` - обновление по токену
- `DELETE /api/codes/{hash}` - удаление по токену

## 🎯 Планы развития

- [ ] **Monaco Editor** - продвинутый редактор кода
- [ ] **Асимметричное шифрование** - с spatie/crypto
- [ ] **Аутентификация пользователей** - регистрация и вход
- [ ] **Личный кабинет** - управление сниппетами
- [ ] **Docker интеграция** - запуск кода в контейнерах
- [ ] **AI-аудит кода** - анализ безопасности кода
- [ ] **Подсветка синтаксиса** - для всех языков
- [ ] **Версионирование** - история изменений сниппетов

## 🤝 Вклад в проект

1. Fork репозитория
2. Создайте feature branch (`git checkout -b feature/amazing-feature`)
3. Commit изменения (`git commit -m 'Add amazing feature'`)
4. Push в branch (`git push origin feature/amazing-feature`)
5. Откройте Pull Request

## 📄 Лицензия

Этот проект лицензирован под MIT License - см. файл [LICENSE](LICENSE) для деталей.

## 👥 Авторы

- **prod-broke-again** - *Инициальная разработка* - [GitHub](https://github.com/prod-broke-again)

## 🙏 Благодарности

- [Laravel](https://laravel.com) - за отличный PHP фреймворк
- [Vue.js](https://vuejs.org) - за прогрессивный JavaScript фреймворк
- [Tailwind CSS](https://tailwindcss.com) - за utility-first CSS
- [Spatie](https://spatie.be) - за качественные PHP пакеты
