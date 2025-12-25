<?php

namespace App\Console\Commands\Concerns;

use function Termwind\render;

trait WithStyledOutput
{
    protected function renderBox(string $title, string $content, string $color = 'green'): void
    {
        render(<<<HTML
            <div class="py-1 px-2">
                <div class="bg-{$color}-500 text-white px-2 font-bold">{$title}</div>
                <div class="mt-1">{$content}</div>
            </div>
        HTML);
    }

    protected function renderSuccess(string $message): void
    {
        render(<<<HTML
            <div class="py-1 px-2">
                <span class="bg-green-500 text-white px-1 mr-1">SUCCESS</span>
                <span class="text-green-500">{$message}</span>
            </div>
        HTML);
    }

    protected function renderError(string $message): void
    {
        render(<<<HTML
            <div class="py-1 px-2">
                <span class="bg-red-500 text-white px-1 mr-1">ERROR</span>
                <span class="text-red-500">{$message}</span>
            </div>
        HTML);
    }

    protected function renderWarning(string $message): void
    {
        render(<<<HTML
            <div class="py-1 px-2">
                <span class="bg-yellow-500 text-black px-1 mr-1">WARNING</span>
                <span class="text-yellow-500">{$message}</span>
            </div>
        HTML);
    }

    protected function renderInfo(string $message): void
    {
        render(<<<HTML
            <div class="py-1 px-2">
                <span class="bg-blue-500 text-white px-1 mr-1">INFO</span>
                <span class="text-blue-500">{$message}</span>
            </div>
        HTML);
    }

    protected function renderTable(array $headers, array $rows): void
    {
        $headerCells = collect($headers)
            ->map(fn ($header) => "<th class=\"px-2\">{$header}</th>")
            ->implode('');

        $tableRows = collect($rows)
            ->map(function ($row) {
                $cells = collect($row)
                    ->map(fn ($cell) => "<td class=\"px-2\">{$cell}</td>")
                    ->implode('');

                return "<tr>{$cells}</tr>";
            })
            ->implode('');

        render(<<<HTML
            <table>
                <thead>
                    <tr class="bg-gray-700 text-white">{$headerCells}</tr>
                </thead>
                <tbody>
                    {$tableRows}
                </tbody>
            </table>
        HTML);
    }

    protected function renderList(array $items, string $style = 'disc'): void
    {
        $listItems = collect($items)
            ->map(fn ($item) => "<li>{$item}</li>")
            ->implode('');

        $tag = $style === 'decimal' ? 'ol' : 'ul';

        render(<<<HTML
            <{$tag} class="list-{$style} px-4">
                {$listItems}
            </{$tag}>
        HTML);
    }
}
