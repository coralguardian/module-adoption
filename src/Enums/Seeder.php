<?php

namespace D4rk0snet\Adoption\Enums;

enum Seeder : string
{
    case SEED_1 = 'seed1';
    case SEED_2 = 'seed2';

    public function getPicture() : string
    {
        return match($this) {
            Seeder::SEED_1 => 'seed1.jpg'
        };
    }
}