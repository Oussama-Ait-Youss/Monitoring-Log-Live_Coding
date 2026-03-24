# Laravel Task Manager: CRUD & Monitoring Guide

A fully functional Task Management system built with Laravel 11, focused on demonstrating **Observability** through logging and **Laravel Telescope**.

## 🚀 Quick Setup

### 1. Install Dependencies

```bash
composer install
php artisan key:generate
```

### 2. Database Configuration

Update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Initialize Database

```bash
php artisan migrate
```

### 4. Create System User

Since auth is disabled, create one user in the database to own the tasks:

```bash
php artisan tinker --execute="App\Models\User::firstOrCreate(['id' => 1], ['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password']);"
```

## 🛰️ Setting Up Telescope (Monitoring)

Follow these steps to ensure the dashboard correctly captures your app's activity.

### 1. Installation

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### 2. Environment Configuration

Ensure your `.env` file is set to record data:

```env
APP_ENV=local
APP_DEBUG=true
TELESCOPE_ENABLED=true
```

### 3. Critical: Authorizing the Dashboard

In `app/Providers/TelescopeServiceProvider.php`, find the `gate()` method and allow local access:

```php
protected function gate(): void
{
    Gate::define('viewTelescope', function ($user = null) {
        return true; // Allow viewing in local environment
    });
}
```

### 4. Enabling Logs in Telescope

Open `config/telescope.php` and set the `LogWatcher` level to `debug` so that `Log::info()` calls are captured:

```php
Watchers\LogWatcher::class => [
    'enabled' => env('TELESCOPE_LOG_WATCHER', true),
    'level' => 'debug',
],
```

## 📋 How to Use the Monitoring System

### Step 1: Execute CRUD Actions

Go to `http://127.0.0.1:8000/tasks` and:

* **Add a Task** → Triggers `Log::info` and a SQL `INSERT`
* **Leave Title Empty** → Triggers `Log::warning` (validation failure)
* **Delete a Task** → Triggers `Log::notice` and a SQL `DELETE`

### Step 2: Check the Filesystem Logs

Open `storage/logs/laravel.log`.
You will see raw log entries such as:

```text
[2026-03-23] local.INFO: Task created without auth
```

### Step 3: Use the Telescope Dashboard

Visit `http://127.0.0.1:8000/telescope`

| Tab        | What to look for      | Why it matters                                 |
| ---------- | --------------------- | ---------------------------------------------- |
| Logs       | Your custom messages  | Confirms the business logic reached its goal   |
| Queries    | Execution time (ms)   | Helps identify slow database performance       |
| Requests   | POST or DELETE status | Shows which SQL query belonged to which action |
| Exceptions | 500 errors            | Gives stack traces to locate failures          |

## 🛠️ Troubleshooting Tips

* **"Scanning..." keeps spinning?**
  Run:

  ```bash
  php artisan telescope:publish
  php artisan config:clear
  ```

* **Logs not appearing in Telescope?**
  Ensure the `filter()` method in `TelescopeServiceProvider` returns `true` for local development.

* **Integrity Constraint Error?**
  Make sure you ran the tinker command in Quick Setup to create **User ID 1**.
