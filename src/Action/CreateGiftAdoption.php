<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\API\AddFriendToGiftAdoption;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\Adoption\Service\AdopteeService;
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
            message: $giftAdoptionModel->getMessage(),
            customAmount: $giftAdoptionModel->getCustomAmount(),
            address: $giftAdoptionModel->getCustomerModel()->getAddress(),
            postalCode: $giftAdoptionModel->getCustomerModel()->getPostalCode(),
            city: $giftAdoptionModel->getCustomerModel()->getCity(),
            country: $giftAdoptionModel->getCustomerModel()->getCountry(),
            firstName: $giftAdoptionModel->getCustomerModel()->getFirstname(),
            lastName: $giftAdoptionModel->getCustomerModel()->getLastname()
        );

        if($giftAdoptionModel->getStripePaymentIntent() !== null) {
            $giftAdoptionEntity->setStripePaymentIntentId($giftAdoptionModel->getStripePaymentIntent()->id);
        }

        $em = DoctrineService::getEntityManager();
        $em->persist($giftAdoptionEntity);
        $em->flush();

        if($giftAdoptionEntity->isPaid() === true) {
            do_action(CoralAdoptionActions::GIFT_ADOPTION_CREATED->value, $giftAdoptionModel, $giftAdoptionEntity->getUuid());
        }

        // Si on a passé des noms directement dans l'adoption, on crée les adoptees.
        if(!empty($giftAdoptionModel->getFriends())) {
            AddFriendToGiftAdoption::addRecipients($giftAdoptionEntity, $giftAdoptionModel->getFriends());
        }
    }
}