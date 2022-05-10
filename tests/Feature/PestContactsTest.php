<?php

use Inertia\Testing\AssertableInertia as Assert;

test('can view contacts', function () {
    $this->actingAs($this->user)
        ->get('/contacts')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->has('contacts.data', 2)
            ->has('contacts.data.0', fn (Assert $assert) => $assert
                ->where('id', 1)
                ->where('name', 'Martin Abbott')
                ->where('phone', '555-111-2222')
                ->where('city', 'Murphyland')
                ->where('deleted_at', null)
                ->has('organization', fn (Assert $assert) => $assert
                    ->where('name', 'Example Organization Inc.')
                )
            )
            ->has('contacts.data.1', fn (Assert $assert) => $assert
                ->where('id', 2)
                ->where('name', 'Lynn Kub')
                ->where('phone', '555-333-4444')
                ->where('city', 'Woodstock')
                ->where('deleted_at', null)
                ->has('organization', fn (Assert $assert) => $assert
                    ->where('name', 'Example Organization Inc.')
                )
            )
        );
});

test('can search for contacts', function () {
    $this->actingAs($this->user)
        ->get('/contacts?search=Martin')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->where('filters.search', 'Martin')
            ->has('contacts.data', 1)
            ->has('contacts.data.0', fn (Assert $assert) => $assert
                ->where('id', 1)
                ->where('name', 'Martin Abbott')
                ->where('phone', '555-111-2222')
                ->where('city', 'Murphyland')
                ->where('deleted_at', null)
                ->has('organization', fn (Assert $assert) => $assert
                    ->where('name', 'Example Organization Inc.')
                )
            )
        );
});

test('cannot view deleted contacts', function () {
    $this->user->account->contacts()->firstWhere('first_name', 'Martin')->delete();

    $this->actingAs($this->user)
        ->get('/contacts')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->has('contacts.data', 1)
            ->where('contacts.data.0.name', 'Lynn Kub')
        );
});

test('can filter to view deleted contacts', function () {
    $this->user->account->contacts()->firstWhere('first_name', 'Martin')->delete();

    $this->actingAs($this->user)
        ->get('/contacts?trashed=with')
        ->assertInertia(fn (Assert $assert) => $assert
            ->component('Contacts/Index')
            ->has('contacts.data', 2)
            ->where('contacts.data.0.name', 'Martin Abbott')
            ->where('contacts.data.1.name', 'Lynn Kub')
        );
});
