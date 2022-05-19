<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdopteeEntity;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\Seeder;
use D4rk0snet\Adoption\Models\AdopteesModel;
use D4rk0snet\Email\Event\NamingDone;
use Exception;
use Hyperion\Doctrine\Service\DoctrineService;
use DateTime;

class AdopteeService
{
    public static function giveNameToAdoptees(AdopteesModel $model) : void
    {
        /** @var AdoptionEntity | null $adoptionOrder */
        $adoptionOrder = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->find($model->getAdoptionUuid());
        if($adoptionOrder === null) {
            throw new Exception("No adoption found", 404);
        }

        if($adoptionOrder->getQuantity() !== count($model->getNames())) {
            throw new Exception("Number of names doesn't match the quantity ordered", 404);
        }

        // Check if names have already been set
        $adoptees = DoctrineService::getEntityManager()->getRepository(AdopteeEntity::class)->findBy(['adoption' => $adoptionOrder]);
        if(count($adoptees)) {
            throw new Exception("Adoptees already have names. Can't rename them.", 400);
        }

        $seeders = Seeder::cases();
        shuffle($seeders);
        $seedIndex = 0;

        foreach($model->getNames() as $name) {
            if (!array_key_exists($seedIndex, $seeders)) {
                $seedIndex = 0;
            }
            $entity = new AdopteeEntity(
                name: $name,
                seeder: $seeders[$seedIndex],
                adoption: $adoptionOrder,
                adopteeDatetime: new DateTime()
            );
            DoctrineService::getEntityManager()->persist($entity);
            $seedIndex++;
        }

        DoctrineService::getEntityManager()->flush();

        // Send email event
        NamingDone::send(
            email: $adoptionOrder->getCustomer()->getEmail(),
            lang: $adoptionOrder->getLang(),
            adoptionType: $adoptionOrder->getAdoptedProduct(),
            quantity: $adoptionOrder->getQuantity()
        );
    }
}