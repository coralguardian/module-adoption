<?php

namespace D4rk0snet\Adoption\Action;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\Adoption\Service\AdopteeService;
use D4rk0snet\CoralCustomer\Enum\CoralCustomerActions;
use D4rk0snet\CoralCustomer\Enum\CoralCustomerFilters;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use Hyperion\Doctrine\Service\DoctrineService;

class CreateAdoption
{
    public static function doAction(AdoptionModel $adoptionModel)
    {
        // Au cas ou le customer n'existe pas on demande sa création
        do_action(CoralCustomerActions::NEW_CUSTOMER->value, $adoptionModel->getCustomerModel());

        // Récupération du customer
        $customerEntity = apply_filters(
            CoralCustomerFilters::GET_CUSTOMER->value,
            $adoptionModel->getCustomerModel()->getEmail(),
            $adoptionModel->getCustomerModel()->getCustomerType()
        );

        // Création de l'entité
        $adoptionEntity = new AdoptionEntity(
            customer: $customerEntity,
            date: new \DateTime(),
            amount: $adoptionModel->getAmount() * $adoptionModel->getQuantity(),
            lang: $adoptionModel->getLang(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity(),
            paymentMethod: $adoptionModel->getPaymentMethod(),
            isPaid: $adoptionModel->getPaymentMethod() === PaymentMethod::CREDIT_CARD && $adoptionModel->getStripePaymentIntent(),
            project: $adoptionModel->getProject(),
            customAmount: $adoptionModel->getCustomAmount(),
            address: $adoptionModel->getCustomerModel()->getAddress(),
            postalCode: $adoptionModel->getCustomerModel()->getPostalCode(),
            city: $adoptionModel->getCustomerModel()->getCity(),
            country: $adoptionModel->getCustomerModel()->getCountry(),
            firstName: $adoptionModel->getCustomerModel()->getFirstname(),
            lastName: $adoptionModel->getCustomerModel()->getLastname()
        );

        if($adoptionModel->getStripePaymentIntent() !== null) {
            $adoptionEntity->setStripePaymentIntentId($adoptionModel->getStripePaymentIntent()->id);
        }

        $em = DoctrineService::getEntityManager();
        $em->persist($adoptionEntity);
        $em->flush();

        if($adoptionEntity->isPaid() === true) {
            do_action(CoralAdoptionActions::ADOPTION_CREATED->value, $adoptionModel, $adoptionEntity);
        }

        // Si on a passé des noms directement dans l'adoption, on crée les adoptees.
        if(!is_null($adoptionModel->getNames())) {
            AdopteeService::handleForAdoption($adoptionEntity, $adoptionModel->getNames());
        }
    }
}