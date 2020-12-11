<?php

use App\Models\LegacyEmployee;
use App\Models\LegacyIndividual;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(LegacyEmployee::class, function (Faker $faker) {
    return [
        'ref_cod_pessoa_fj' => function () {
            return factory(LegacyIndividual::class)->create()->idpes;
        },
        'Matricula' => $faker->randomDigitNotNull,
        'senha' => $faker->randomDigitNotNull,
        'ativo' => 1
    ];
});
