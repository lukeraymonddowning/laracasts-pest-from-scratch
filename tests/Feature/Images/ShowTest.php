<?php

use Illuminate\Support\Facades\Storage;

it('can show supported image formats and options', function ($path, $options) {
    Storage::fake()->put($path, file_get_contents(__DIR__ . "/../../fixtures/{$path}"));

    $response = $this->get(route('image', ['path' => $path, ...$options]));
    $response->assertOk();

    expect($response->streamedContent())->not->toBeEmpty()->toBeString();
})->with([
    'example.png',
    'example.jpg',
    'example.webp',
])->with([
    [['w' => 40, 'h' => 40, 'fit' => 'crop']],
    [['w' => 50, 'h' => 50, 'fit' => 'crop']],
    [['w' => 10, 'h' => 10, 'fit' => 'crop']],
    [['w' => 0, 'h' => 0, 'fit' => 'crop']],
]);
