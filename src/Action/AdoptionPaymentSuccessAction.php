<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\API\AdoptionEndpoint;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Coralguardian\Event\AdoptionOrder;
use Hyperion\Doctrine\Service\DoctrineService;
use Stripe\PaymentIntent;

class AdoptionPaymentSuccessAction
{
    public static function doAction(PaymentIntent $stripePaymentIntent)
    {
        if ($stripePaymentIntent->metadata->type !== AdoptionEndpoint::PAYMENT_INTENT_TYPE) {
            return;
        }

        /** @var AdoptionEntity $entity */
        $entity = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->find($stripePaymentIntent->metadata->adoption_uuid);

        if ($entity === null) {
            return;
        }

        $entity->setStripePaymentIntentId($stripePaymentIntent->id);
        $entity->setIsPaid(true);
        DoctrineService::getEntityManager()->flush();

        AdoptionOrder::sendEvent($entity);
    }
}
