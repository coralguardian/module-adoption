<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\Coralguardian\Entity\CompanyCustomerEntity;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Entity\IndividualCustomerEntity;
use DateTime;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Stripe\Service\BillingService;
use Hyperion\Stripe\Service\CustomerService;
use Stripe\PaymentIntent;

class AdoptionService
{
    public static function createAdoption(AdoptionModel $adoptionModel) : AdoptionEntity
    {
        $customer = DoctrineService::getEntityManager()
            ->getRepository(CustomerEntity::class)
            ->find($adoptionModel->getCustomerUUID());

        if ($customer === null) {
            throw new \Exception("Customer not found");
        }

        $newAdoptionEntity = new AdoptionEntity(
            customer: $customer,
            date: new DateTime(),
            amount: $adoptionModel->getAmount(),
            lang: $adoptionModel->getLang(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity()
        );

        DoctrineService::getEntityManager()->persist($newAdoptionEntity);
        DoctrineService::getEntityManager()->flush();

        return $newAdoptionEntity;
    }

    public static function createGiftAdoption(GiftAdoptionModel $adoptionModel) : GiftAdoption
    {
        $customer = DoctrineService::getEntityManager()
            ->getRepository(CustomerEntity::class)
            ->find($adoptionModel->getCustomerUUID());

        if ($customer === null) {
            throw new \Exception("Customer not found");
        }

        $newGiftAdoptionEntity = new GiftAdoption(
            customer: $customer,
            date: new DateTime(),
            amount: $adoptionModel->getAmount(),
            lang: $adoptionModel->getLang(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity(),
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

        return $newGiftAdoptionEntity;
    }

    public static function createInvoiceAndGetPaymentIntent($adoptionModel) : PaymentIntent
    {
        // Est ce que le client est une entreprise ?
        $customer = DoctrineService::getEntityManager()
            ->getRepository(CustomerEntity::class)
            ->find($adoptionModel->getUuid());

        if ($customer instanceof IndividualCustomerEntity) {
            $customerId = CustomerService::getOrCreateIndividualCustomer(
                email: $customer->getEmail(),
                firstName: $customer->getFirstname(),
                lastName:  $customer->getLastname()
            )->id;
        } else {
            $customerId = CustomerService::getOrCreateCompanyCustomer(
                email: $customer->getEmail(),
                companyName: $customer->getCompanyName(),
                mainContactName: $customer->getMainContactName()
            )->id;
        }

        BillingService::createLineItem(
            customerId: $customerId,
            priceId: $adoptionModel->getAdoptedProduct()->getProductPriceId(),
            quantity: $adoptionModel->getQuantity()
        );

        $bill = BillingService::createBill($customerId);

        return BillingService::finalizeAndGetPaymentIntent($bill);
    }
}
