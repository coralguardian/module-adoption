<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\CoralCustomer\Enum\CoralCustomerActions;
use D4rk0snet\CoralCustomer\Enum\CoralCustomerFilters;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use Hyperion\Doctrine\Service\DoctrineService;

class CreateAdoption
{
    public static function doAction(AdoptionModel $adoptionModel)
    {
        $customerEntity = null;

        // Au cas ou le customer n'existe pas on demande sa création
        do_action(CoralCustomerActions::NEW_CUSTOMER->value, $adoptionModel->getCustomerModel());

        // Récupération du customer
        $customerEntity = apply_filters(
            CoralCustomerFilters::GET_CUSTOMER->value,
            $customerEntity,
            $adoptionModel->getCustomerModel()->getEmail(),
            $adoptionModel->getCustomerModel()->getCustomerType()
        );

        // Création de l'entité
        $adoptionEntity = new AdoptionEntity(
            customer: $customerEntity,
            date: new \DateTime(),
            amount: $adoptionModel->getAmount(),
            lang: $adoptionModel->getLang(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity(),
            paymentMethod: $adoptionModel->getPaymentMethod(),
            isPaid: $adoptionModel->getPaymentMethod() === PaymentMethod::CREDIT_CARD && $adoptionModel->getStripePaymentIntent()->status === 'succeeded',
            project: $adoptionModel->getProject()
        );

        if($adoptionModel->getStripePaymentIntent() !== null) {
            $adoptionEntity->setStripePaymentIntentId($adoptionModel->getStripePaymentIntent()->id);
        }

        $em = DoctrineService::getEntityManager();
        $em->persist($adoptionEntity);
        $em->flush();

        do_action(CoralAdoptionActions::NEW_ADOPTION->value, $adoptionModel, $adoptionEntity);
    }
}