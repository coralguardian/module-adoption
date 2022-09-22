<?php

namespace D4rk0snet\Adoption\Filter;

use D4rk0snet\Adoption\Entity\GiftAdoption;
use Hyperion\Doctrine\Service\DoctrineService;

class GetGiftAdoptionFilter
{
    public static function doAction(string $giftAdoptionUUID)
    {
        $em = DoctrineService::getEntityManager();

        return $em->getRepository(GiftAdoption::class)->find($giftAdoptionUUID);
    }
}