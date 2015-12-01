<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(GatherUp\Models\User::class, function (Faker\Generator $faker)
{
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(GatherUp\Models\Team::class, function(Faker\Generator $faker)
{
    return [
        'name' => $faker->name,
    ];
});

$factory->define(GatherUp\Models\AuthToken::class, function(Faker\Generator $faker)
{
    return [
        'token' => uniqid(),
    ];
});

$factory->define(GatherUp\Models\TeamKey::class, function(Faker\Generator $faker)
{
    $rsa = new GatherUp\Encryption\RsaEncryption();
    $keys = $rsa->createKeyPair();

    return [
        'public_key' => $keys->publicKey,
        'private_key' => $keys->privateKey,
    ];
});

$factory->define(GatherUp\Models\Event::class, function(Faker\Generator $faker)
{
    return [
        'title' => $faker->sentence(),
        'started_at' => $faker->dateTime(),
        'notes' => $faker->realText(),
    ];
});