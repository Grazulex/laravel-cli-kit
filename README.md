# Laravel CLI Kit

A production-ready Laravel 12 starter kit for building beautiful and interactive command-line applications. Includes laravel/prompts, termwind, and spatie/async for modern CLI development.

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## Features

- **Interactive Prompts** - Beautiful forms with laravel/prompts (text, select, confirm, multiselect, search)
- **Styled Output** - Tailwind-like styling with nunomaduro/termwind
- **Progress Indicators** - Spinners and progress bars for long-running tasks
- **Async/Parallel** - Concurrent task execution with spatie/async
- **Modern Testing** - Pest PHP testing framework
- **Docker Ready** - Development environment included
- **Reusable Traits** - Pre-built concerns for common CLI patterns

## Requirements

- Docker & Docker Compose
- Or: PHP 8.2+, Composer 2.x

## Quick Start

### With Docker (Recommended)

```bash
# Clone the repository
git clone https://github.com/grazulex/laravel-cli-kit.git
cd laravel-cli-kit

# Copy environment file
cp .env.example .env

# Build and install
docker compose build
docker compose run --rm app list

# Run tests
docker compose run --rm test
```

### Without Docker

```bash
# Clone and install
git clone https://github.com/grazulex/laravel-cli-kit.git
cd laravel-cli-kit
composer install

# Configure
cp .env.example .env
php artisan key:generate

# Verify
./vendor/bin/pest
```

## Project Structure

```
laravel-cli-kit/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── Concerns/              # Reusable traits
│   │       │   ├── WithAsyncTasks.php
│   │       │   ├── WithProgressBar.php
│   │       │   ├── WithSpinner.php
│   │       │   └── WithStyledOutput.php
│   │       └── YourCommand.php        # Your commands here
│   ├── Models/
│   └── Providers/
├── config/
├── database/
├── routes/
│   └── console.php                    # Console routes
├── tests/
│   ├── Feature/Commands/
│   └── Unit/
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Creating Commands

### Basic Command

```bash
php artisan make:command GreetCommand
```

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GreetCommand extends Command
{
    protected $signature = 'greet {name?}';
    protected $description = 'Greet a user';

    public function handle(): int
    {
        $name = $this->argument('name') ?? 'World';
        $this->info("Hello, {$name}!");

        return self::SUCCESS;
    }
}
```

### Using Laravel Prompts

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\text;
use function Laravel\Prompts\select;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\search;

class SetupCommand extends Command
{
    protected $signature = 'app:setup';
    protected $description = 'Interactive setup wizard';

    public function handle(): int
    {
        // Text input with placeholder and validation
        $name = text(
            label: 'What is your name?',
            placeholder: 'John Doe',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 2 => 'Name must be at least 2 characters.',
                default => null
            }
        );

        // Password input
        $password = password(
            label: 'Enter your password',
            required: true
        );

        // Single select
        $role = select(
            label: 'Select your role',
            options: [
                'admin' => 'Administrator',
                'editor' => 'Editor',
                'viewer' => 'Viewer',
            ],
            default: 'viewer'
        );

        // Multi-select
        $features = multiselect(
            label: 'Which features do you want?',
            options: [
                'notifications' => 'Email Notifications',
                'api' => 'API Access',
                'reports' => 'Advanced Reports',
            ],
            default: ['notifications']
        );

        // Confirmation
        $confirmed = confirm(
            label: 'Do you confirm these settings?',
            default: true
        );

        // Search with callback
        $user = search(
            label: 'Search for a user',
            options: function (string $value) {
                if (strlen($value) < 2) return [];

                return collect(['John', 'Jane', 'Bob', 'Alice'])
                    ->filter(fn ($name) => str_contains(strtolower($name), strtolower($value)))
                    ->values()
                    ->all();
            }
        );

        $this->info("Setup complete for {$name}!");

        return self::SUCCESS;
    }
}
```

### Using Styled Output (Termwind)

```php
<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\WithStyledOutput;
use Illuminate\Console\Command;

class ReportCommand extends Command
{
    use WithStyledOutput;

    protected $signature = 'report:show';
    protected $description = 'Display a styled report';

    public function handle(): int
    {
        // Success message
        $this->renderSuccess('Operation completed successfully!');

        // Error message
        $this->renderError('Something went wrong!');

        // Warning message
        $this->renderWarning('Deprecated feature detected.');

        // Info message
        $this->renderInfo('Processing 100 items...');

        // Styled box
        $this->renderBox('Summary', 'All tasks completed.', 'blue');

        // Table
        $this->renderTable(
            ['ID', 'Name', 'Status'],
            [
                ['1', 'Task A', 'Complete'],
                ['2', 'Task B', 'Pending'],
                ['3', 'Task C', 'Failed'],
            ]
        );

        // List
        $this->renderList([
            'First item',
            'Second item',
            'Third item',
        ]);

        // Numbered list
        $this->renderList([
            'Step one',
            'Step two',
            'Step three',
        ], 'decimal');

        return self::SUCCESS;
    }
}
```

### Using Progress Bars

```php
<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\WithProgressBar;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    use WithProgressBar;

    protected $signature = 'import:data';
    protected $description = 'Import data with progress';

    public function handle(): int
    {
        $items = range(1, 100);

        // Using Laravel Prompts progress
        $results = $this->withProgress(
            label: 'Importing items',
            items: $items,
            callback: function ($item) {
                usleep(50000); // Simulate work
                return $item * 2;
            },
            hint: 'This may take a while...'
        );

        $this->info('Imported ' . count($results) . ' items.');

        return self::SUCCESS;
    }
}
```

### Using Spinners

```php
<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\WithSpinner;
use Illuminate\Console\Command;

class FetchCommand extends Command
{
    use WithSpinner;

    protected $signature = 'fetch:data';
    protected $description = 'Fetch data with spinner';

    public function handle(): int
    {
        // Spinner while executing a task
        $result = $this->withSpinner(
            message: 'Fetching data from API...',
            callback: function () {
                sleep(3); // Simulate API call
                return ['users' => 100, 'posts' => 500];
            }
        );

        $this->info("Fetched {$result['users']} users and {$result['posts']} posts.");

        return self::SUCCESS;
    }
}
```

### Using Async/Parallel Tasks

```php
<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\WithAsyncTasks;
use Illuminate\Console\Command;

class BatchCommand extends Command
{
    use WithAsyncTasks;

    protected $signature = 'batch:process';
    protected $description = 'Process tasks in parallel';

    public function handle(): int
    {
        // Using Laravel's Concurrency facade
        $results = $this->runConcurrently([
            fn () => $this->processTask('A'),
            fn () => $this->processTask('B'),
            fn () => $this->processTask('C'),
        ]);

        // Using spatie/async pool for more control
        $tasks = [
            'task1' => fn () => $this->heavyComputation(1),
            'task2' => fn () => $this->heavyComputation(2),
            'task3' => fn () => $this->heavyComputation(3),
        ];

        $results = $this->runAsync($tasks, concurrency: 3);

        foreach ($results as $key => $result) {
            $this->info("{$key}: {$result}");
        }

        return self::SUCCESS;
    }

    private function processTask(string $name): string
    {
        sleep(1);
        return "Task {$name} completed";
    }

    private function heavyComputation(int $id): int
    {
        usleep(500000);
        return $id * 100;
    }
}
```

## Available Traits

### WithStyledOutput

Beautiful terminal output using Termwind's Tailwind-like syntax.

| Method | Description |
|--------|-------------|
| `renderSuccess($message)` | Green success message |
| `renderError($message)` | Red error message |
| `renderWarning($message)` | Yellow warning message |
| `renderInfo($message)` | Blue info message |
| `renderBox($title, $content, $color)` | Styled box with title |
| `renderTable($headers, $rows)` | Formatted table |
| `renderList($items, $style)` | Bullet or numbered list |

### WithProgressBar

Progress indicators for iterative tasks.

| Method | Description |
|--------|-------------|
| `withProgress($label, $items, $callback, $hint)` | Laravel Prompts progress bar |
| `processWithProgress($label, $items, $callback)` | Simple inline progress |

### WithSpinner

Loading spinners for long-running operations.

| Method | Description |
|--------|-------------|
| `withSpinner($message, $callback)` | Spinner while executing callback |
| `spinWhile($message, $condition, $interval)` | Spinner while condition is true |

### WithAsyncTasks

Concurrent and parallel task execution.

| Method | Description |
|--------|-------------|
| `runConcurrently($tasks)` | Laravel Concurrency facade |
| `runAsync($tasks, $concurrency)` | Spatie async pool |
| `parallel($closures)` | Alias for runConcurrently |
| `defer($callback)` | Deferred execution |

## Testing

```bash
# Run all tests
docker compose run --rm test

# Or without Docker
./vendor/bin/pest

# Run specific test
./vendor/bin/pest tests/Feature/Commands/ArtisanTest.php

# With coverage
./vendor/bin/pest --coverage
```

### Testing Commands

```php
<?php

it('can run setup command interactively', function () {
    $this->artisan('app:setup')
        ->expectsQuestion('What is your name?', 'John')
        ->expectsQuestion('Select your role', 'admin')
        ->expectsConfirmation('Do you confirm?', 'yes')
        ->assertSuccessful();
});

it('shows error on invalid input', function () {
    $this->artisan('greet')
        ->expectsOutput('Hello, World!')
        ->assertSuccessful();
});
```

## Development Commands

```bash
# Code formatting
docker compose run --rm shell ./vendor/bin/pint

# List all commands
docker compose run --rm app list

# Create new command
docker compose run --rm shell php artisan make:command MyCommand

# Run shell for development
docker compose run --rm shell
```

## Environment Configuration

Key `.env` variables:

```env
APP_NAME="My CLI App"
APP_ENV=local
APP_DEBUG=true

# Database (optional, for commands that need it)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## Building for Production

### Create PHAR Archive (Optional)

For standalone distribution, consider using [Laravel Zero](https://laravel-zero.com/) or [Box](https://github.com/box-project/box) to compile your CLI into a single PHAR file.

## Tips & Best Practices

1. **Use PromptsForMissingInput** - Automatically prompt for missing arguments:
   ```php
   use Illuminate\Contracts\Console\PromptsForMissingInput;

   class MyCommand extends Command implements PromptsForMissingInput
   {
       // Arguments will be prompted if not provided
   }
   ```

2. **Validate early** - Use prompt validation to catch errors immediately

3. **Provide defaults** - Always offer sensible defaults for optional inputs

4. **Use spinners for API calls** - Any operation > 1 second should show feedback

5. **Parallel for I/O** - Use async for file operations, API calls, not CPU work

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

- [Laravel](https://laravel.com) - The PHP Framework
- [Laravel Prompts](https://laravel.com/docs/prompts) - Beautiful CLI Forms
- [Termwind](https://github.com/nunomaduro/termwind) - Tailwind for CLI
- [Spatie Async](https://github.com/spatie/async) - Parallel Processing
- [Pest PHP](https://pestphp.com) - Testing Framework

## Support

- [Issues](https://github.com/grazulex/laravel-cli-kit/issues)
- [Discussions](https://github.com/grazulex/laravel-cli-kit/discussions)
