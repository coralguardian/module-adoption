<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use Hyperion\Doctrine\Service\DoctrineService;

class AdoptionService
{
    public static function updateGiftAdoptionWithMessage(GiftAdoption $giftAdoption, GiftAdoptionModel $adoptionModel): GiftAdoption
    {
        $giftAdoption->setMessage($adoptionModel->getMessage());
        if (null !== $adoptionModel->getSendOn()) {
            $giftAdoption->setSendOn($adoptionModel->getSendOn());
        }
        DoctrineService::getEntityManager()->flush();

        return $giftAdoption;
    }
}
