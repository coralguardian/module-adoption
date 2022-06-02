<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\Coralguardian\Entity\CompanyCustomerEntity;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Model\IndividualCustomerModel;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use D4rk0snet\GiftCode\Service\GiftCodeService;
use DateTime;
use Doctrine\DBAL\Types\ConversionException;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\Stripe\Service\BillingService;
use Hyperion\Stripe\Service\CustomerService;
use Stripe\PaymentIntent;

class AdoptionService
{
    public static function createAdoption(AdoptionModel $adoptionModel): AdoptionEntity
    {
        try {
            $customer = DoctrineService::getEntityManager()
                ->getRepository(CustomerEntity::class)
                ->find($adoptionModel->getCustomerUUID());

            if ($customer === null) {
                throw new \Exception("Customer not found", 400);
            }
        } catch (ConversionException $exception) {
            throw new \Exception("Customer not found", 400);
        }

        $newAdoptionEntity = new AdoptionEntity(
            customer: $customer,
            date: new DateTime(),
            amount: $adoptionModel->getAmount(),
            lang: $adoptionModel->getLang(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity(),
            paymentMethod: $adoptionModel->getPaymentMethod(),
            isPaid: false
        );

        DoctrineService::getEntityManager()->persist($newAdoptionEntity);
        DoctrineService::getEntityManager()->flush();

        return $newAdoptionEntity;
    }

    public static function createGiftAdoption(GiftAdoptionModel $adoptionModel): GiftAdoption
    {
        $customer = DoctrineService::getEntityManager()
            ->getRepository(CustomerEntity::class)
            ->find($adoptionModel->getCustomerUUID());

        if ($customer === null) {
            throw new \Exception("Customer not found");
        }

        if ($customer instanceof IndividualCustomerModel && count($adoptionModel->getFriends()) === 0) {
            throw new \Exception("No friends for this gift adoption");
        }

        $newGiftAdoptionEntity = new GiftAdoption(
            customer: $customer,
            date: new DateTime(),
            amount: $adoptionModel->getAmount(),
            lang: $adoptionModel->getLang(),
            adoptedProduct: $adoptionModel->getAdoptedProduct(),
            quantity: $adoptionModel->getQuantity(),
            paymentMethod: $adoptionModel->getPaymentMethod(),
            isPaid: false,
            sendToFriend: $adoptionModel->isSendToFriend(),
            sendOn: $adoptionModel->getSendOn(),
            message: $adoptionModel->getMessage()
        );

        DoctrineService::getEntityManager()->persist($newGiftAdoptionEntity);

        if ($customer instanceof CompanyCustomerEntity) {
            for ($i = 0; $i < $adoptionModel->getQuantity(); $i++) {
                $giftCode = new GiftCodeEntity(
                    giftCode: GiftCodeService::createGiftCode(bin2hex(random_bytes(20))),
                    giftAdoption: $newGiftAdoptionEntity,
                    productQuantity: 1
                );
                DoctrineService::getEntityManager()->persist($giftCode);

                // en mode entreprise on ne renseigne pas les friend au moment de créer la gift adoption
            }
        } else {
            // il n'y a qu'un ami pour les particuliers
            $friend = $adoptionModel->getFriends()[0];

            $giftCode = new GiftCodeEntity(
                giftCode: GiftCodeService::createGiftCode(bin2hex(random_bytes(20))),
                giftAdoption: $newGiftAdoptionEntity,
                productQuantity: $adoptionModel->getQuantity()
            );
            DoctrineService::getEntityManager()->persist($giftCode);
            $friendEntity = new Friend(
                friendFirstname: $friend->getFriendFirstname(),
                friendLastname: $friend->getFriendLastname(),
                friendEmail: $friend->getFriendEmail(),
                giftCode: $giftCode
            );

            DoctrineService::getEntityManager()->persist($friendEntity);
        }

        DoctrineService::getEntityManager()->flush();

        return $newGiftAdoptionEntity;
    }

    public static function createInvoiceAndGetPaymentIntent(AdoptionModel $adoptionModel): PaymentIntent
    {
        // Est ce que le client est une entreprise ?
        $customer = DoctrineService::getEntityManager()
            ->getRepository(CustomerEntity::class)
            ->find($adoptionModel->getCustomerUUID());

        if ($customer instanceof CompanyCustomerEntity) {
            $customerId = CustomerService::getOrCreateCompanyCustomer(
                email: $customer->getEmail(),
                companyName: $customer->getCompanyName(),
                mainContactName: $customer->getMainContactName()
            )->id;
        } else {
            $customerId = CustomerService::getOrCreateIndividualCustomer(
                email: $customer->getEmail(),
                firstName: $customer->getFirstname(),
                lastName: $customer->getLastname()
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
