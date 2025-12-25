<?php

it('can run artisan list command', function () {
    $this->artisan('list')
        ->assertSuccessful();
});

it('can run artisan inspire command', function () {
    $this->artisan('inspire')
        ->assertSuccessful();
});

it('can run artisan env command', function () {
    $this->artisan('env')
        ->assertSuccessful();
});
