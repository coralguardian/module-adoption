<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use DateTime;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Stripe\Service\BillingService;
use Hyperion\Stripe\Service\CustomerService;
use Stripe\PaymentIntent;

class AdoptionService
{
    public static function createAdoption(AdoptionModel $adoptionModel) : string
    {
        $newAdoptionEntity = new AdoptionEntity(
            firstname: $adoptionModel->getFirstname(),
            lastname: $adoptionModel->getLastname(),
            address: $adoptionModel->getAddress(),
            city: $adoptionModel->getCity(),
            country: $adoptionModel->getCountry(),
            email: $adoptionModel->getEmail(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity(),
            orderDate: new DateTime(),
            amount: $adoptionModel->getAmount(),
            lang: $adoptionModel->getLang()
        );

        DoctrineService::getEntityManager()->persist($newAdoptionEntity);
        DoctrineService::getEntityManager()->flush();

        return $newAdoptionEntity->getUuid();
    }

    public static function createGiftAdoption(GiftAdoptionModel $adoptionModel) : string
    {
        $newGiftAdoptionEntity = new GiftAdoption(
            firstname: $adoptionModel->getFirstname(),
            lastname: $adoptionModel->getLastname(),
            address: $adoptionModel->getAddress(),
            city: $adoptionModel->getCity(),
            country: $adoptionModel->getCountry(),
            email: $adoptionModel->getEmail(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity(),
            orderDate: new DateTime(),
            amount: $adoptionModel->getAmount(),
            lang: $adoptionModel->getLang(),
            friendFirstname: $adoptionModel->getFriendFirstname(),
            friendLastname: $adoptionModel->getLastname(),
            friendAddress: $adoptionModel->getAddress(),
            friendCity: $adoptionModel->getCity(),
            friendEmail: $adoptionModel->getFriendEmail(),
            message: $adoptionModel->getMessage(),
            sendOn: $adoptionModel->getSendOn()
        );

        DoctrineService::getEntityManager()->persist($newGiftAdoptionEntity);
        DoctrineService::getEntityManager()->flush();

        return $newGiftAdoptionEntity->getUuid();
    }


    public static function createInvoiceAndGetPaymentIntent(AdoptionModel $adoptionModel) : PaymentIntent
    {
        $customerId = CustomerService::getOrCreateCustomer(
            email: $adoptionModel->getEmail(),
            firstName: $adoptionModel->getFirstname(),
            lastName: $adoptionModel->getLastname(),
            metadata: ['type' => 'individual']
        )->id;

        BillingService::createLineItem(
            customerId: $customerId,
            priceId: $adoptionModel->getAdoptedProduct()->getProductPriceId(),
            quantity: $adoptionModel->getQuantity()
        );

        $bill = BillingService::createBill($customerId);

        return BillingService::finalizeAndGetPaymentIntent($bill);
    }
}
