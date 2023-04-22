<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdopteeEntity;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Enums\AdoptedProduct;
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

        $adoptees = self::createAdoptees($giftCodeEntity->getGiftAdoption(), $model->getNames());

        $giftCodeEntity->setAdoptees($adoptees);
        $giftCodeEntity->setUsed(true);
        $giftCodeEntity->setUsedOn(new DateTime());
        DoctrineService::getEntityManager()->flush();

        $friend = DoctrineService::getEntityManager()->getRepository(Friend::class)->findOneBy(["giftCode" => $giftCodeEntity->getUuid()]);

        if (null !== $friend) {
            NamingDone::sendEvent($giftCodeEntity);
        }
    }

    public static function handleForAdoption(AdoptionEntity $adoptionEntity, array $names): void
    {
        if($adoptionEntity->getQuantity() !== count($names)) {
            throw new Exception("Number of names doesn't match the quantity ordered", 404);
        }

        // Check if names have already been set
        $adoptees = DoctrineService::getEntityManager()->getRepository(AdopteeEntity::class)->findBy(['adoption' => $adoptionEntity]);
        if(count($adoptees)) {
            throw new Exception("Adoptees already have names. Can't rename them.", 400);
        }

        self::createAdoptees($adoptionEntity, $names);

        // Send email event
        NamingDone::sendEvent($adoptionEntity);
    }

    private static function createAdoptees(AdoptionEntity $adoption, array $names): ArrayCollection
    {
        $adoptees = new ArrayCollection();
        $seeders = Seeder::randomizeSeeder($adoption->getProject());
        $pictures = AdoptedProduct::getRandomizedProductImages($adoption->getAdoptedProduct(), $adoption->getProject());
        $seedersCount = count($seeders);
        $picturesCount = count($pictures);

        foreach($names as $index => $name) {
            $selectedSeeder = $seeders[$index % $seedersCount];
            $selectedPicture = $pictures[$index % $picturesCount];

            $entity = new AdopteeEntity(
                name: $name,
                seeder: $selectedSeeder,
                adoption: $adoption,
                adopteeDatetime: new DateTime(),
                picture: $selectedPicture
            );
            DoctrineService::getEntityManager()->persist($entity);

            $adoptees->add($entity);
        }

        DoctrineService::getEntityManager()->flush();
        return $adoptees;
    }
}