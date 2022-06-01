<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\API\GiftAdoptionEndpoint;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Service\RedirectionService;
use D4rk0snet\Coralguardian\Entity\CompanyCustomerEntity;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Event\AdoptionOrder;
use D4rk0snet\Coralguardian\Model\IndividualCustomerModel;
use D4rk0snet\FiscalReceipt\Service\FiscalReceiptService;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use Hyperion\Doctrine\Service\DoctrineService;
use Stripe\PaymentIntent;

class GiftAdoptionPaymentSuccessAction
{
    public static function doAction(PaymentIntent $stripePaymentIntent)
    {
        if ($stripePaymentIntent->metadata->type !== GiftAdoptionEndpoint::PAYMENT_INTENT_TYPE) {
            return;
        }

        // Save Payment reference in order
        $giftAdoptionUuid = $stripePaymentIntent->metadata->gift_adoption_uuid;

        /** @var GiftAdoption $entity */
        $entity = DoctrineService::getEntityManager()->getRepository(GiftAdoption::class)->find($giftAdoptionUuid);
        if ($entity === null) {
            return;
        }
        $entity->setStripePaymentIntentId($stripePaymentIntent->id);
        $entity->setIsPaid(true);
        DoctrineService::getEntityManager()->flush();

        $codeToSend = [];
        if (!$entity->isSendToFriend()) {
            $codeToSend = $entity->getGiftCodes()->map(function(GiftCodeEntity $giftCodeEntity) { return $giftCodeEntity->getGiftCode(); } );
        }

        // Send email event with data needed
        AdoptionOrder::send(
            email: $entity->getCustomer()->getEmail(),
            lang: $entity->getLang()->value,
            quantity: $entity->getQuantity(),
            receiptFileUrl: FiscalReceiptService::getURl($giftAdoptionUuid),
            nextStepUrl: RedirectionService::buildRedirectionUrl($entity),
            codeSentTofriend: $entity->isSendToFriend(),
            isCompany: $entity->getCustomer() instanceof CompanyCustomerEntity,
            codeToSend: $codeToSend
        );
    }
}
