<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create([
        'account_id' => Account::create(['name' => 'Acme Corporation'])->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'johndoe@example.com',
        'owner' => true,
    ]);

    $this->user->account->organizations()->createMany([
        [
            'name' => 'Apple',
            'email' => 'info@apple.com',
            'phone' => '647-943-4400',
            'address' => '1600-120 Bremner Blvd',
            'city' => 'Toronto',
            'region' => 'ON',
            'country' => 'CA',
            'postal_code' => 'M5J 0A8',
        ], [
            'name' => 'Microsoft',
            'email' => 'info@microsoft.com',
            'phone' => '877-568-2495',
            'address' => 'One Microsoft Way',
            'city' => 'Redmond',
            'region' => 'WA',
            'country' => 'US',
            'postal_code' => '98052',
        ],
    ]);
});

it('can view organizations', function () {
    $this->actingAs($this->user)
        ->get('/organizations')
        ->assertInertia(fn(Assert $assert) => $assert
            ->component('Organizations/Index')
            ->has('organizations.data', 2)
            ->has('organizations.data.0', fn(Assert $assert) => $assert
                ->where('id', 1)
                ->where('name', 'Apple')
                ->where('phone', '647-943-4400')
                ->where('city', 'Toronto')
                ->where('deleted_at', null)
            )
            ->has('organizations.data.1', fn(Assert $assert) => $assert
                ->where('id', 2)
                ->where('name', 'Microsoft')
                ->where('phone', '877-568-2495')
                ->where('city', 'Redmond')
                ->where('deleted_at', null)
            )
        );
});

it('can search for organizations', function () {
    $this->actingAs($this->user)
        ->get('/organizations?search=Apple')
        ->assertInertia(fn(Assert $assert) => $assert
            ->component('Organizations/Index')
            ->where('filters.search', 'Apple')
            ->has('organizations.data', 1)
            ->has('organizations.data.0', fn(Assert $assert) => $assert
                ->where('id', 1)
                ->where('name', 'Apple')
                ->where('phone', '647-943-4400')
                ->where('city', 'Toronto')
                ->where('deleted_at', null)
            )
        );
});

it('cannot view deleted organizations', function () {
    $this->user->account->organizations()->firstWhere('name', 'Microsoft')->delete();

    $this->actingAs($this->user)
        ->get('/organizations')
        ->assertInertia(fn(Assert $assert) => $assert
            ->component('Organizations/Index')
            ->has('organizations.data', 1)
            ->where('organizations.data.0.name', 'Apple')
        );
});

it('can filter to view deleted organizations', function () {
    $this->user->account->organizations()->firstWhere('name', 'Microsoft')->delete();

    $this->actingAs($this->user)
        ->get('/organizations?trashed=with')
        ->assertInertia(fn(Assert $assert) => $assert
            ->component('Organizations/Index')
            ->has('organizations.data', 2)
            ->where('organizations.data.0.name', 'Apple')
            ->where('organizations.data.1.name', 'Microsoft')
        );
});
