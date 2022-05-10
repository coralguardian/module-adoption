<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Email\Event\AdoptionOrder;
use Hyperion\Doctrine\Service\DoctrineService;

class PaymentSuccessAction
{
    /**
     * @todo: Gérer le cas ensuite pour les entreprises
     */
    public static function doAction($stripePaymentIntent)
    {
        if($stripePaymentIntent['metadata']['type'] !== 'adoption') {
            return;
        }

        // Save Payment reference in order
        $adoptionUuid = $stripePaymentIntent['metadata']['adoption_uuid'];

        /** @var AdoptionEntity $entity */
        $entity = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->find($adoptionUuid);
        if($entity === null) {
            return;
        }
        $entity->setStripePaymentIntentId($stripePaymentIntent['id']);
        DoctrineService::getEntityManager()->flush();

        // Send email event with data needed
        AdoptionOrder::send(
            email: $entity->getEmail(),
            lang: $entity->getLang()->value,
            quantity: $entity->getQuantity(),
            adoptionType: $entity->getAdoptedProduct()->value
        );
    }
}