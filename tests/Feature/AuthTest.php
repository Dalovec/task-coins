<?php

use App\Models\User;
use Illuminate\Support\Str;
use function Pest\Laravel\{post, get};

it('can create user', function () {
    $values = [
        'email' => fake()->email(),
        'password' => 'MySecretPassword838467*'
    ];

    $response = post('/auth/issue-token', $values);

    $user = User::query()
        ->where('email', $values['email']);

    $this->assertTrue($user->exists());

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'expires_at'
        ]);
});

it('will fail when email is invalid', function () {
    $values = [
        'email' => 'invalid-email',
        'password' => Str::password(10, true, true, true),
    ];

    $response = post('/auth/issue-token', $values);

    $response->assertStatus(422);
});

it('will fail when password is invalid', function () {
    $values = [
        'email' => fake()->email(),
        'password' => 'invalid-password',
    ];

    $response = post('/auth/issue-token', $values);

    $response->assertStatus(422);
});

it('can revoke token', function () {
    $values = [
        'email' => fake()->email(),
        'password' => 'MySecretPassword838467*'
    ];

    $response = post('/auth/issue-token', $values);

    $token = $response->json('token');

    $response = get('/auth/revoke-token', ['Authorization' => 'Bearer ' . $token]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure(['message']);
});
