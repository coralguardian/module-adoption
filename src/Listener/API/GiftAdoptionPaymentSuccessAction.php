<?php

namespace D4rk0snet\Adoption\Listener\Action;

use D4rk0snet\Adoption\API\GiftAdoptionEndpoint;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Coralguardian\Event\GiftCodeSent;
use D4rk0snet\Coralguardian\Event\GiftOrder;
use Hyperion\Doctrine\Service\DoctrineService;
use Stripe\PaymentIntent;

class GiftAdoptionPaymentSuccessAction
{
    public static function doAction(PaymentIntent $stripePaymentIntent)
    {
        if ($stripePaymentIntent->metadata->type !== GiftAdoptionEndpoint::PAYMENT_INTENT_TYPE) {
            return;
        }

        /** @var GiftAdoption $entity */
        $entity = DoctrineService::getEntityManager()->getRepository(GiftAdoption::class)->find($stripePaymentIntent->metadata->gift_adoption_uuid);
        if ($entity === null) {
            return;
        }

        $entity->setStripePaymentIntentId($stripePaymentIntent->id);
        $entity->setIsPaid(true);
        DoctrineService::getEntityManager()->flush();

        GiftOrder::sendEvent($entity);

        if (!$entity->getCustomer() instanceof CompanyCustomerEntity) {
            GiftCodeSent::sendEvent($entity->getGiftCodes()->first());
        }
    }
}
