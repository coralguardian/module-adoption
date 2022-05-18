<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\API\GiftAdoptionEndpoint;
use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Email\Event\AdoptionOrder;
use D4rk0snet\FiscalReceipt\Endpoint\GetFiscalReceiptEndpoint;
use Hyperion\Doctrine\Service\DoctrineService;
use Stripe\PaymentIntent;

class GiftAdoptionPaymentSuccessAction
{
    /**
     * @todo: Gérer le cas ensuite pour les entreprises
     */
    public static function doAction(PaymentIntent $stripePaymentIntent)
    {
        if ($stripePaymentIntent->metadata->type !== GiftAdoptionEndpoint::PAYMENT_INTENT_TYPE) {
            return;
        }

        // Save Payment reference in order
        $giftAdoptionUuid = $stripePaymentIntent->metadata->adoption_uuid;

        /** @var AdoptionEntity $entity */
        $entity = DoctrineService::getEntityManager()->getRepository(GiftAdoption::class)->find($giftAdoptionUuid);
        if ($entity === null) {
            return;
        }
        $entity->setStripePaymentIntentId($stripePaymentIntent->id);
        DoctrineService::getEntityManager()->flush();

        // Send email event with data needed
        AdoptionOrder::send(
            email: $entity->getEmail(),
            lang: $entity->getLang()->value,
            quantity: $entity->getQuantity(),
            receiptFileUrl: GetFiscalReceiptEndpoint::getUrl()."?".GetFiscalReceiptEndpoint::ORDER_UUID_PARAM."=".$entity->getUuid(),
            nextStepUrl: "www.google.fr"
        );
    }
}
