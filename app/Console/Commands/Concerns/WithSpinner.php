<?php

namespace App\Console\Commands\Concerns;

use Closure;

use function Laravel\Prompts\spin;

trait WithSpinner
{
    protected function withSpinner(string $message, Closure $callback): mixed
    {
        return spin(
            message: $message,
            callback: $callback
        );
    }

    protected function spinWhile(string $message, Closure $condition, int $intervalMs = 100): void
    {
        spin(
            message: $message,
            callback: function () use ($condition, $intervalMs) {
                while ($condition()) {
                    usleep($intervalMs * 1000);
                }
            }
        );
    }
}
