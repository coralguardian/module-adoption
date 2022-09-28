<?php

namespace D4rk0snet\Adoption\Enums;

use D4rk0snet\CoralOrder\Enums\Project;

enum Seeder: string
{
    case DULAH = 'dulah';
    case JONAS = 'jonas';
    case MURDHI = 'murdhi';
    case MUSLIM = 'muslim';
    case SAHRIL = 'sahril';
    case SUHAR = 'suhar';
    case VALENTINA = 'valentina';
    case HELENA = "helena";
    case JAVIER = "javier";
    case MARINA = "marina";
    case NACHO = "nacho";
    case RAFA = "rafa";
    case SALVA = "salva";
    case ZAIDA = "zaida";

    // case TEAM = 'team'; @todo: a été utilisé pour MigrationScript, devrait potentiellement être utilisé pour nommer les transplanteurs des récifs
    // @todo: en l'état au 25/08/22 cela provoque une erreur lorsque l'on souhaite attribué un transplanteur à un corail cause du Enum::cases cf AdopteeService

    public static function randomizeSeeder(Project $project): array
    {
        $seeders = Seeder::getSeedersByProject($project);
        shuffle($seeders);
        return $seeders;
    }

    public static function getSeedersByProject(Project $project): array
    {
        switch ($project) {
            case Project::INDONESIA :
                return [
                    Seeder::DULAH,
                    Seeder::JONAS,
                    Seeder::MURDHI,
                    Seeder::MUSLIM,
                    Seeder::SAHRIL,
                    Seeder::SUHAR,
                    Seeder::VALENTINA
                ];
            case Project::SPAIN:
                return [
                    Seeder::HELENA,
                    Seeder::JAVIER,
                    Seeder::NACHO,
                    Seeder::MARINA,
                    Seeder::SALVA,
                    Seeder::ZAIDA,
                    Seeder::RAFA
                ];
            default:
                throw new \Exception("Unhandled ProjectEnum");
        }
    }

    public function getPicture(): string
    {
        return match($this) {
            Seeder::DULAH => 'indonesia/dulah.jpg',
            Seeder::JONAS => 'indonesia/jonas.jpg',
            Seeder::MURDHI => 'indonesia/murdhi.jpg',
            Seeder::MUSLIM => 'indonesia/muslim.jpg',
            Seeder::SAHRIL => 'indonesia/sahril.jpg',
            Seeder::SUHAR => 'indonesia/suhar.jpg',
            Seeder::VALENTINA => 'indonesia/valentina.jpg',
            Seeder::HELENA => "spain/helena.jpg",
            Seeder::JAVIER => "spain/javier.jpg",
            Seeder::NACHO => "spain/nacho.jpg",
            Seeder::MARINA => "spain/marina.jpg",
            Seeder::SALVA => "spain/salva.jpg",
            Seeder::ZAIDA => "spain/zaida.jpg",
            Seeder::RAFA => "spain/rafa.jpg"
        };
    }

    public function getName(): string
    {
        return match ($this) {
            Seeder::DULAH => 'Dulah',
            Seeder::JONAS => 'Jonas',
            Seeder::MURDHI => 'Murdhi',
            Seeder::MUSLIM => 'Muslim',
            Seeder::SAHRIL => 'Sahril',
            Seeder::SUHAR => 'Suhar',
            Seeder::VALENTINA => 'Valentina',
            Seeder::HELENA => "Helena",
            Seeder::JAVIER => "Javier",
            Seeder::NACHO => "Nacho",
            Seeder::MARINA => "Marina",
            Seeder::SALVA => "Salva",
            Seeder::ZAIDA => "Zaida",
            Seeder::RAFA => "Rafa"
        };
    }
}