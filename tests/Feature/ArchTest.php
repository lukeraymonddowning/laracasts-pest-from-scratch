<?php

use App\Http\Controllers\ContactsController;

it('does not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

it('uses the redirect facade for redirecting')
    ->expect(['back', 'redirect', 'to_route'])
    ->not->toBeUsedIn('App\\Http\\Controllers\\');
