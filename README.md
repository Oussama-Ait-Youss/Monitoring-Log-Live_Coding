# Laravel To-Do CRUD with Logging Hooks

A clean Laravel 11.x implementation of a To-Do list, designed specifically for testing logging and monitoring implementations.

## Setup Instructions
1. **Clone & Install**:
   `composer install && npm install && npm run dev`
2. **Environment**:
   `cp .env.example .env` (Set your DB_DATABASE, etc.)
3. **Migrate**:
   `php artisan migrate`
4. **Run**:
   `php artisan serve`

## Monitoring Strategy
This project is pre-instrumented with comments where the following should be added:
- **Logging**: Inside `TaskController`, use the `Log` facade to track CRUD lifecycle events.
- **Telescope**: Once installed via `composer require laravel/telescope`, the `TelescopeServiceProvider` will automatically begin monitoring the Requests and Queries triggered by this controller.
- **Exceptions**: Validation failures and Authorization exceptions will be logged in `storage/logs/laravel.log`.