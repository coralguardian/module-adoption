<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Email\Event\AdoptionOrder;
use D4rk0snet\FiscalReceipt\Model\FiscalReceiptModel;
use D4rk0snet\FiscalReceipt\Service\FiscalReceiptService;
use Hyperion\Doctrine\Service\DoctrineService;
use Stripe\PaymentIntent;

class PaymentSuccessAction
{
    /**
     * @todo: Gérer le cas ensuite pour les entreprises
     */
    public static function doAction(PaymentIntent $stripePaymentIntent)
    {
        if($stripePaymentIntent->metadata->type !== 'adoption') {
            return;
        }

        // Save Payment reference in order
        $adoptionUuid = $stripePaymentIntent->metadata->adoption_uuid;

        /** @var AdoptionEntity $entity */
        $entity = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->find($adoptionUuid);
        if($entity === null) {
            return;
        }
        $entity->setStripePaymentIntentId($stripePaymentIntent->id);
        DoctrineService::getEntityManager()->flush();

        $fiscalReceiptModel = new FiscalReceiptModel(
            articles: '45/407',
            receiptCode: 1,
            customerFullName: $entity->getFirstname(). " ".$entity->getLastname(),
            customerAddress: $entity->getAddress(),
            customerPostalCode: "xxx",
            customerCity: $entity->getCity(),
            fiscalReductionPercentage: 60,
            priceWord: "soixante",
            price: $entity->getAmount(),
            date: new \DateTime(),
            orderUuid: $entity->getUuid()
        );

        $fileURl = FiscalReceiptService::createReceipt($fiscalReceiptModel);

        // Send email event with data needed
        AdoptionOrder::send(
            email: $entity->getEmail(),
            lang: $entity->getLang()->value,
            quantity: $entity->getQuantity(),
            receiptFileUrl: $fileURl,
            nextStepUrl: "www.google.fr"
        );
    }
}