<?php

use App\Console\Commands\Concerns\WithStyledOutput;
use App\Console\Commands\Concerns\WithProgressBar;
use App\Console\Commands\Concerns\WithSpinner;
use App\Console\Commands\Concerns\WithAsyncTasks;

it('can load WithStyledOutput trait', function () {
    expect(trait_exists(WithStyledOutput::class))->toBeTrue();
});

it('can load WithProgressBar trait', function () {
    expect(trait_exists(WithProgressBar::class))->toBeTrue();
});

it('can load WithSpinner trait', function () {
    expect(trait_exists(WithSpinner::class))->toBeTrue();
});

it('can load WithAsyncTasks trait', function () {
    expect(trait_exists(WithAsyncTasks::class))->toBeTrue();
});
