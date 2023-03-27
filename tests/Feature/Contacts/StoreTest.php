<?php

use App\Models\Contact;
use App\Models\Organization;
use function Pest\Faker\faker;

it('can store a contact', function () {
    login()->post('/contacts', [
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'email' => fake()->email,
        'phone' => fake()->e164PhoneNumber,
        'address' => '1 Test Street',
        'city' => 'Testerfield',
        'region' => 'Derbyshire',
        'country' => fake()->randomElement(['us', 'ca']),
        'postal_code' => fake()->postcode,
    ])
        ->assertRedirect('/contacts')
        ->assertSessionHas('success', 'Contact created.');

    expect(Contact::latest()->first())
        ->first_name->toBeString()->not->toBeEmpty()
        ->last_name->toBeString()->not->toBeEmpty()
        ->email->toBeString()->toContain('@')
        ->phone->toBePhoneNumber()
        ->region->toBe('Derbyshire')
        ->country->toBeIn(['us', 'ca']);
});

it('requires the organization belong to the same account as the user', function (Closure $organisationResolver, $errors = null) {
    login();

    $organization = $organisationResolver(Auth::user());

    $response = $this->post('/contacts', [
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'email' => fake()->email,
        'phone' => fake()->e164PhoneNumber,
        'address' => '1 Test Street',
        'city' => 'Testerfield',
        'region' => 'Derbyshire',
        'country' => fake()->randomElement(['us', 'ca']),
        'postal_code' => fake()->postcode,
        'organization_id' => $organization->getKey(),
    ]);

    if ($errors) {
        $response->assertInvalid($errors);
    } else {
        $response->assertValid();
    }
})->with([
    [fn ($user) => Organization::factory()->create(['account_id' => $user->account_id])],
    [fn ($user) => Organization::factory()->create(), ['organization_id']],
]);


