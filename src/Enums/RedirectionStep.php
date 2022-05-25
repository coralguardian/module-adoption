<?php

namespace D4rk0snet\Adoption\Enums;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;

enum RedirectionStep: string
{
    case ADOPTION = 'adoption';
    case RECIPIENT = 'recipient';

    public function getAdoptionClass() : string
    {
        return match ($this) {
            RedirectionStep::ADOPTION => AdoptionEntity::class,
            RedirectionStep::RECIPIENT => GiftAdoption::class
        };
    }

    public static function getEnumBasedOnClass(string $className): self
    {
        return match ($className) {
            AdoptionEntity::class => RedirectionStep::ADOPTION,
            GiftAdoption::class => RedirectionStep::RECIPIENT
        };
    }
}