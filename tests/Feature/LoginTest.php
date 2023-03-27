<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('can log in a user', function () {
    $credentials = [
        'email' => 'ted.faro@villain.com',
        'password' => 'password',
    ];

    $user = User::factory()->create($credentials);

    $this->post(route('login'), $credentials);

    expect(Auth::user())->toBe($user);
});
