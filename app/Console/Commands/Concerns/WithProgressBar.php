<?php

namespace App\Console\Commands\Concerns;

use Closure;

use function Laravel\Prompts\progress;

trait WithProgressBar
{
    protected function withProgress(
        string $label,
        iterable $items,
        Closure $callback,
        ?string $hint = null
    ): array {
        return progress(
            label: $label,
            steps: $items,
            callback: $callback,
            hint: $hint
        );
    }

    protected function processWithProgress(
        string $label,
        array $items,
        Closure $callback
    ): array {
        $results = [];
        $total = count($items);
        $processed = 0;

        $this->output->write("\n");

        foreach ($items as $key => $item) {
            $processed++;
            $percentage = round(($processed / $total) * 100);

            $results[$key] = $callback($item, $key);

            $this->output->write("\r{$label}: [{$processed}/{$total}] {$percentage}%");
        }

        $this->output->write("\n");

        return $results;
    }
}
