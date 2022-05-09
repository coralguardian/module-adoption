<?php

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Models\AdoptionModel;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Stripe\Service\BillingService;
use Hyperion\Stripe\Service\CustomerService;
use Stripe\PaymentIntent;

class AdoptionService
{
    /*
     * Persist in database our adoption
     */
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
            amount: $adoptionModel->getAmount()
        );

        DoctrineService::getEntityManager()->persist($newAdoptionEntity);
        DoctrineService::getEntityManager()->flush();

        return $newAdoptionEntity->getUuid();
    }

    public static function createInvoiceAndGetPaymentIntent(AdoptionModel $adoptionModel) : PaymentIntent
    {
        $customerId = CustomerService::getCustomerIdByEmail($adoptionModel->getEmail()) ??
                      CustomerService::createCustomer(
                          email: $adoptionModel->getEmail(),
                          firstName: $adoptionModel->getFirstname(),
                          lastName: $adoptionModel->getLastname(),
                          metadata: ['type' => 'individual']
                      );

        BillingService::createLineItem(
            customerId: $customerId,
            priceId: $adoptionModel->getAdoptedProduct()->getProductPriceId(),
            quantity: $adoptionModel->getQuantity()
        );

        $bill = BillingService::createBill($customerId);

        return BillingService::finalizeAndGetPaymentIntent($bill);
    }
}