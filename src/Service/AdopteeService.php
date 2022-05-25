<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdopteeEntity;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\Seeder;
use D4rk0snet\Adoption\Models\AdopteesModel;
use D4rk0snet\Certificate\Endpoint\GetCertificateEndpoint;
use D4rk0snet\Coralguardian\Event\NamingDone;
use D4rk0snet\FiscalReceipt\Service\FiscalReceiptService;
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
        $pictures = $adoptionOrder->getAdoptedProduct()->getProductImages();
        shuffle($pictures);
        $pictureIndex = 0; // on ne peut pas réutiliser seedIndex car il y a plus de transplanteur que d'images de récifs

        foreach($model->getNames() as $name) {
            if (!array_key_exists($seedIndex, $seeders)) {
                $seedIndex = 0;
            }
            if (!array_key_exists($pictureIndex, $pictures)) {
                $pictureIndex = 0;
            }
            $entity = new AdopteeEntity(
                name: $name,
                seeder: $seeders[$seedIndex],
                adoption: $adoptionOrder,
                adopteeDatetime: new DateTime(),
                picture: $pictures[$pictureIndex]
            );
            DoctrineService::getEntityManager()->persist($entity);
            $seedIndex++;
            $pictureIndex++;
        }

        DoctrineService::getEntityManager()->flush();

        // Send email event
        NamingDone::send(
            email: $adoptionOrder->getCustomer()->getEmail(),
            lang: $adoptionOrder->getLang(),
            adoptionType: $adoptionOrder->getAdoptedProduct(),
            quantity: $adoptionOrder->getQuantity(),
            fiscalReceiptUrl: FiscalReceiptService::getURl($model->getAdoptionUuid()),
            certificateUrl: GetCertificateEndpoint::getUrl([GetCertificateEndpoint::ORDER_UUID_PARAM => $adoptionOrder->getUuid()])
        );
    }
}