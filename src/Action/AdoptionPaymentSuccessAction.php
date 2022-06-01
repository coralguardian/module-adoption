<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\API\AdoptionEndpoint;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Service\RedirectionService;
use D4rk0snet\Coralguardian\Entity\CompanyCustomerEntity;
use D4rk0snet\Coralguardian\Event\AdoptionOrder;
use D4rk0snet\FiscalReceipt\Service\FiscalReceiptService;
use Hyperion\Doctrine\Service\DoctrineService;
use Stripe\PaymentIntent;

class AdoptionPaymentSuccessAction
{
    public static function doAction(PaymentIntent $stripePaymentIntent)
    {
        if ($stripePaymentIntent->metadata->type !== AdoptionEndpoint::PAYMENT_INTENT_TYPE) {
            return;
        }

        // Save Payment reference in order
        $adoptionUuid = $stripePaymentIntent->metadata->adoption_uuid;
        /** @var AdoptionEntity $entity */
        $entity = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->find($adoptionUuid);

        if ($entity === null) {
            return;
        }

        $entity->setStripePaymentIntentId($stripePaymentIntent->id);
        $entity->setIsPaid(true);
        DoctrineService::getEntityManager()->flush();

        // Send email event with data needed
        AdoptionOrder::send(
            email: $entity->getCustomer()->getEmail(),
            lang: $entity->getLang()->value,
            quantity: $entity->getQuantity(),
            receiptFileUrl: FiscalReceiptService::getURl($adoptionUuid),
            nextStepUrl: RedirectionService::buildRedirectionUrl($entity),
            isCompany: $entity->getCustomer() instanceof CompanyCustomerEntity
        );
    }
}
