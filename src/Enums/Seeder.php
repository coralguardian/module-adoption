<?php

namespace D4rk0snet\Adoption\Enums;

enum Seeder : string
{
    case DULAH = 'dulah';
    case JONAS = 'jonas';
    case MURDHI = 'murdhi';
    case MUSLIM = 'muslim';
    case SAHRIL = 'sahril';
    case SUHAR = 'suhar';
    case VALENTINA = 'valentina';

    public static function randomizeSeeder() : array
    {
        $seeders = Seeder::cases();
        shuffle($seeders);
        return $seeders;
    }

    public function getPicture() : string
    {
        return match($this) {
            Seeder::DULAH => 'dulah.jpg',
            Seeder::JONAS => 'jonas.jpg',
            Seeder::MURDHI => 'murdhi.jpg',
            Seeder::MUSLIM => 'muslim.jpg',
            Seeder::SAHRIL => 'sahril.jpg',
            Seeder::SUHAR => 'suhar.jpg',
            Seeder::VALENTINA => 'valentina.jpg',
        };
    }
}