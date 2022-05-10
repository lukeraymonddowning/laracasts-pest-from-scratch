<?php

use App\Models\User;

it('can store a contact', function () {
    $this->actingAs(User::factory()->create())
        ->post('/contacts', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'foo@bar.com',
            'phone' => '01234567890',
            'address' => '1 Test Street',
            'city' => 'Testerfield',
            'region' => 'Derbyshire',
            'country' => 'US',
            'postal_code' => 'T35T 1NG',
        ])
        ->assertRedirect('/contacts')
        ->assertSessionHas('success', 'Contact created.');
});
