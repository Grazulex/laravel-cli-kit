<?php

namespace App\Console\Commands\Concerns;

use Closure;
use Illuminate\Support\Facades\Concurrency;
use Spatie\Async\Pool;

trait WithAsyncTasks
{
    protected function runConcurrently(array $tasks): array
    {
        return Concurrency::run($tasks);
    }

    protected function runAsync(array $tasks, int $concurrency = 20): array
    {
        $pool = Pool::create()->concurrency($concurrency);
        $results = [];

        foreach ($tasks as $key => $task) {
            $pool->add($task)->then(function ($output) use (&$results, $key) {
                $results[$key] = $output;
            })->catch(function (\Throwable $e) use (&$results, $key) {
                $results[$key] = ['error' => $e->getMessage()];
            });
        }

        $pool->wait();

        return $results;
    }

    protected function parallel(array $closures): array
    {
        return Concurrency::run($closures);
    }

    protected function defer(Closure $callback): void
    {
        Concurrency::defer($callback);
    }
}
