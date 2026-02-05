# TaskFlow Pro - Task Management Module

Production-ready task management system built with Laravel 10, Livewire 3, and Inertia.js/Vue 3.

## Features

- ✅ Complete CRUD operations for tasks
- ✅ Dual interface (Livewire + Inertia/Vue)
- ✅ RESTful API with Sanctum authentication
- ✅ Advanced filtering and search
- ✅ Priority and status management
- ✅ Responsive design (TailwindCSS)
- ✅ Comprehensive test suite (48 tests)
- ✅ PSR-12 compliant
- ✅ Production-ready code

## Tech Stack

- Laravel 10
- Livewire 3
- Inertia.js + Vue 3
- TailwindCSS
- MySQL
- PHPUnit
- Pint (PSR-12)
- Larastan

## Installation
```bash
# Clone repository
git clone https://github.com/Roland-Siyapze/test-fullstack-laravel.git
cd task-flow

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start servers
php artisan serve
npm run dev
```

## Testing
```bash
php artisan test
./vendor/bin/pint
./vendor/bin/phpstan analyse
```

## Login Connection
```bash
username : test@example.com
password : 12345678
```

## API Endpoints
```
GET    /api/tasks          - List all tasks
POST   /api/tasks          - Create task
GET    /api/tasks/{id}     - Show task
PUT    /api/tasks/{id}     - Update task
DELETE /api/tasks/{id}     - Delete task
```

## License

This project was developed as a technical test.