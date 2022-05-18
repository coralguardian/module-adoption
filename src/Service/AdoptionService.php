<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Models\CompanyAdoptionModel;
use D4rk0snet\Adoption\Models\CompanyGiftAdoptionModel;
use D4rk0snet\Adoption\Models\IndividualAdoptionModel;
use D4rk0snet\Adoption\Models\IndividualGiftAdoptionModel;
use DateTime;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Stripe\Service\BillingService;
use Hyperion\Stripe\Service\CustomerService;
use Stripe\PaymentIntent;

class AdoptionService
{
    public static function createIndividualAdoption(IndividualAdoptionModel $adoptionModel) : string
    {
        $customer = \D4rk0snet\Coralguardian\Service\CustomerService::getOrCreateIndividualCustomer(
            email: $adoptionModel->getEmail(),
            firstname: $adoptionModel->getFirstname(),
            lastname: $adoptionModel->getLastname(),
            address: $adoptionModel->getAddress(),
            city: $adoptionModel->getCity(),
            country: $adoptionModel->getCountry()
        );

        $newAdoptionEntity = new AdoptionEntity(
            customer: $customer,
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

    public static function createCompanyAdoption(CompanyAdoptionModel $adoptionModel) : string
    {
        $customer = \D4rk0snet\Coralguardian\Service\CustomerService::getOrCreateCompanyCustomer(
            email: $adoptionModel->getEmail(),
            companyName: $adoptionModel->getCompanyName(),
            mainContactName: $adoptionModel->getMainContactName(),
            address: $adoptionModel->getAddress(),
            city: $adoptionModel->getCity(),
            country: $adoptionModel->getCountry()
        );

        $newAdoptionEntity = new AdoptionEntity(
            customer: $customer,
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

    public static function createIndividualGiftAdoption(IndividualGiftAdoptionModel $adoptionModel) : string
    {
        $customer = \D4rk0snet\Coralguardian\Service\CustomerService::getOrCreateIndividualCustomer(
            email: $adoptionModel->getEmail(),
            firstname: $adoptionModel->getFirstname(),
            lastname: $adoptionModel->getLastname(),
            address: $adoptionModel->getAddress(),
            city: $adoptionModel->getCity(),
            country: $adoptionModel->getCountry()
        );

        $newGiftAdoptionEntity = new GiftAdoption(
            customerEntity: $customer,
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

    public static function createCompanyGiftAdoption(CompanyGiftAdoptionModel $adoptionModel) : string
    {
        $customer = \D4rk0snet\Coralguardian\Service\CustomerService::getOrCreateCompanyCustomer(
            email: $adoptionModel->getEmail(),
            companyName: $adoptionModel->getCompanyName(),
            mainContactName: $adoptionModel->getMainContactName(),
            address: $adoptionModel->getAddress(),
            city: $adoptionModel->getCity(),
            country: $adoptionModel->getCountry()
        );

        $newGiftAdoptionEntity = new GiftAdoption(
            customerEntity: $customer,
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



    public static function createInvoiceAndGetPaymentIntent($adoptionModel) : PaymentIntent
    {
        if ($adoptionModel instanceof IndividualAdoptionModel || $adoptionModel instanceof IndividualGiftAdoptionModel) {
            $customerId = CustomerService::getOrCreateIndividualCustomer(
                email: $adoptionModel->getEmail(),
                firstName: $adoptionModel->getFirstname(),
                lastName:  $adoptionModel->getLastname()
            )->id;
        } else if ($adoptionModel instanceof CompanyAdoptionModel || $adoptionModel instanceof CompanyGiftAdoptionModel) {
            $customerId = CustomerService::getOrCreateCompanyCustomer(
                email: $adoptionModel->getEmail(),
                companyName: $adoptionModel->getCompanyName(),
                mainContactName: $adoptionModel->getMainContactName()
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
