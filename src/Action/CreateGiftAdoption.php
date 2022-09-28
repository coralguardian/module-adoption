<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\CoralCustomer\Enum\CoralCustomerActions;
use D4rk0snet\CoralCustomer\Enum\CoralCustomerFilters;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use Hyperion\Doctrine\Service\DoctrineService;

class CreateGiftAdoption
{
    public static function doAction(GiftAdoptionModel $giftAdoptionModel)
    {
        // Au cas ou le customer n'existe pas on demande sa création
        do_action(CoralCustomerActions::NEW_CUSTOMER->value, $giftAdoptionModel->getCustomerModel());

        // Récupération du customer
        $customerEntity = apply_filters(
            CoralCustomerFilters::GET_CUSTOMER->value,
            $giftAdoptionModel->getCustomerModel()->getEmail(),
            $giftAdoptionModel->getCustomerModel()->getCustomerType()
        );

        // Création de l'entité
        $giftAdoptionEntity = new GiftAdoption(
            customer: $customerEntity,
            date: new \DateTime(),
            amount: $giftAdoptionModel->getAmount() * $giftAdoptionModel->getQuantity(),
            lang: $giftAdoptionModel->getLang(),
            adoptedProduct: $giftAdoptionModel->getAdoptedProduct(),
            quantity: $giftAdoptionModel->getQuantity(),
            paymentMethod: $giftAdoptionModel->getPaymentMethod(),
            isPaid: $giftAdoptionModel->getPaymentMethod() === PaymentMethod::CREDIT_CARD && $giftAdoptionModel->getStripePaymentIntent(),
            sendToFriend: $giftAdoptionModel->isSendToFriend(),
            project: $giftAdoptionModel->getProject(),
            sendOn: $giftAdoptionModel->getSendOn(),
            message: $giftAdoptionModel->getMessage()
        );

        if($giftAdoptionModel->getStripePaymentIntent() !== null) {
            $giftAdoptionEntity->setStripePaymentIntentId($giftAdoptionModel->getStripePaymentIntent()->id);
        }

        $em = DoctrineService::getEntityManager();
        $em->persist($giftAdoptionEntity);
        $em->flush();

        do_action(CoralAdoptionActions::NEW_GIFT_ADOPTION->value, $giftAdoptionModel, $giftAdoptionEntity->getUuid());
    }
}