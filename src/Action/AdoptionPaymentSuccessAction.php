<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\API\AdoptionEndpoint;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Email\Event\AdoptionOrder;
use D4rk0snet\FiscalReceipt\Endpoint\GetFiscalReceiptEndpoint;
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
        DoctrineService::getEntityManager()->flush();

        $urlParts = parse_url(GetFiscalReceiptEndpoint::getUrl()."?".GetFiscalReceiptEndpoint::ORDER_UUID_PARAM."=".$entity->getUuid());

        // Send email event with data needed
        AdoptionOrder::send(
            email: $entity->getCustomer()->getEmail(),
            lang: $entity->getLang()->value,
            quantity: $entity->getQuantity(),
            receiptFileUrl: $urlParts["host"].$urlParts["path"]."?".$urlParts["query"],
            nextStepUrl: "www.google.fr"
        );
    }
}
