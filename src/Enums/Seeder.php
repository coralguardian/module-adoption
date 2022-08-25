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
    // case TEAM = 'team'; @todo: a été utilisé pour MigrationScript, devrait potentiellement être utilisé pour nommer les transplanteurs des récifs
    // @todo: en l'état au 25/08/22 cela provoque une erreur lorsque l'on souhaite attribué un transplanteur à un corail cause du Enum::cases cf AdopteeService

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

    public function getName() : string
    {
        return match($this) {
            Seeder::DULAH => 'Dulah',
            Seeder::JONAS => 'Jonas',
            Seeder::MURDHI => 'Murdhi',
            Seeder::MUSLIM => 'Muslim',
            Seeder::SAHRIL => 'Sahril',
            Seeder::SUHAR => 'Suhar',
            Seeder::VALENTINA => 'Valentina',
        };
    }
}