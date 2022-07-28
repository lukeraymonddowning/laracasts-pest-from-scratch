<?php

use Illuminate\Support\Facades\Storage;

it('can show supported image formats and options', function ($path, $options) {
    Storage::fake();
    Storage::put($path, file_get_contents(__DIR__ . "/../../fixtures/{$path}"));

    $response = $this->get(route('image', ['path' => $path, ...$options]));
    $response->assertOk();

    expect($response->streamedContent())->not->toBeEmpty()->toBeString();
})->with([
    ['path' => 'example.png', ['w' => 40, 'h' => 40, 'fit' => 'crop']],
    ['path' => 'example.jpg', ['w' => 40, 'h' => 40, 'fit' => 'crop']],
    ['path' => 'example.webp', ['w' => 40, 'h' => 40, 'fit' => 'crop']],
]);
