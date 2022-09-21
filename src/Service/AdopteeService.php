<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdopteeEntity;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Enums\Seeder;
use D4rk0snet\Adoption\Models\AdopteesModel;
use D4rk0snet\Coralguardian\Event\NamingDone;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Hyperion\Doctrine\Service\DoctrineService;
use DateTime;

class AdopteeService
{
    public static function giveNameToAdoptees(AdopteesModel $model) : void
    {
        /** @var AdoptionEntity | null $adoptionEntity */
        $adoptionEntity = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->find($model->getAdoptionUuid());
        if($adoptionEntity === null) {
            throw new Exception("No adoption found", 404);
        }

        if ($adoptionEntity instanceof GiftAdoption) {
            self::handleForGiftAdoption($model);
        } else {
            self::handleForAdoption($adoptionEntity, $model);
        }
    }

    private static function handleForGiftAdoption(AdopteesModel $model): void
    {
        /** @var GiftCodeEntity $giftCodeEntity */
        $giftCodeEntity = DoctrineService::getEntityManager()->getRepository(GiftCodeEntity::class)->findOneBy(["giftCode" => $model->getGiftCode()]);

        if (null === $giftCodeEntity) {
            throw new Exception("No gift code found", 404);
        }

        if($giftCodeEntity->getProductQuantity() !== count($model->getNames())) {
            throw new Exception("Number of names doesn't match the quantity ordered", 404);
        }

        // Check if names have already been set
        if($giftCodeEntity->isUsed()) {
            throw new Exception("Adoptees already have names. Can't rename them.", 400);
        }

        $adoptees = self::createAdoptees($giftCodeEntity->getGiftAdoption(), $model);

        $giftCodeEntity->setAdoptees($adoptees);
        $giftCodeEntity->setUsed(true);
        $giftCodeEntity->setUsedOn(new DateTime());
        DoctrineService::getEntityManager()->flush();

        $friend = DoctrineService::getEntityManager()->getRepository(Friend::class)->findOneBy(["giftCode" => $giftCodeEntity->getUuid()]);

        if (null !== $friend) {
            NamingDone::sendEvent($giftCodeEntity);
        }
    }

    private static function handleForAdoption(AdoptionEntity $adoptionEntity, AdopteesModel $adopteesModel): void
    {
        if($adoptionEntity->getQuantity() !== count($adopteesModel->getNames())) {
            throw new Exception("Number of names doesn't match the quantity ordered", 404);
        }

        // Check if names have already been set
        $adoptees = DoctrineService::getEntityManager()->getRepository(AdopteeEntity::class)->findBy(['adoption' => $adoptionEntity]);
        if(count($adoptees)) {
            throw new Exception("Adoptees already have names. Can't rename them.", 400);
        }

        self::createAdoptees($adoptionEntity, $adopteesModel);

        // Send email event
        NamingDone::sendEvent($adoptionEntity);
    }

    private static function createAdoptees(AdoptionEntity $adoption, AdopteesModel $adopteesModel): ArrayCollection
    {
        $adoptees = new ArrayCollection();
        $seeders = Seeder::cases();
        shuffle($seeders);
        $seedIndex = 0;
        $pictures = $adoption->getAdoptedProduct()->getProductImages();
        shuffle($pictures);
        $pictureIndex = 0; // on ne peut pas réutiliser seedIndex car il y a plus de transplanteur que d'images de récifs

        foreach($adopteesModel->getNames() as $name) {
            if (!array_key_exists($seedIndex, $seeders)) {
                $seedIndex = 0;
            }
            if (!array_key_exists($pictureIndex, $pictures)) {
                $pictureIndex = 0;
            }
            $entity = new AdopteeEntity(
                name: $name,
                seeder: $seeders[$seedIndex],
                adoption: $adoption,
                adopteeDatetime: new DateTime(),
                picture: $pictures[$pictureIndex]
            );
            DoctrineService::getEntityManager()->persist($entity);

            $adoptees->add($entity);

            $seedIndex++;
            $pictureIndex++;
        }

        DoctrineService::getEntityManager()->flush();
        return $adoptees;
    }
}