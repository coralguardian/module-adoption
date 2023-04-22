<?php

namespace D4rk0snet\Adoption\Listener;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\CoralCustomer\Model\CustomerModel;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use D4rk0snet\CoralOrder\Enums\Project;
use D4rk0snet\CoralOrder\Model\ProductOrderModel;
use D4rk0snet\CoralOrder\Service\ProductService;
use D4rk0snet\Donation\Enums\DonationRecurrencyEnum;
use Hyperion\Stripe\Service\StripeService;
use JsonMapper;
use Stripe\SetupIntent;

/**
 * Cette classe écoute l'action NEW_ORDER du module order
 */
class NewOrder
{
    public static function doAction(SetupIntent $setupIntent)
    {
        $mapper = new JsonMapper();
        $mapper->bExceptionOnMissingData = true;
        $mapper->postMappingMethod = 'afterMapping';

        $customerModel = $mapper->map(
            json_decode($setupIntent->metadata['customer'], false, 512, JSON_THROW_ON_ERROR),
            new CustomerModel()
        );

        /** @var ProductOrderModel $productOrdered */
        $productOrdered = $mapper->map(
            json_decode($setupIntent->metadata['productOrdered'], false, 512, JSON_THROW_ON_ERROR),
            new ProductOrderModel()
        );

        // Récupère le prix pour le produit depuis stripe
        $stripeProduct = ProductService::getProduct($productOrdered->getKey(), $productOrdered->getProject(), $productOrdered->getVariant());
        $stripePrice = StripeService::getStripeClient()->prices->retrieve($stripeProduct->default_price);
        $project = Project::from($productOrdered->getProject());

        // On vérifie si il n'y a pas de montant custom
        $customAmount = null;

        if($setupIntent->metadata['donationOrdered'] !== null) {
            $donations = json_decode($setupIntent->metadata['donationOrdered'], false, 512, JSON_THROW_ON_ERROR);

            // On isole un éventuel don induit par une modification du prix total
            $filterResults = array_filter($donations, static function ($donationOrderData) {
                return
                    $donationOrderData->donationRecurrency === DonationRecurrencyEnum::ONESHOT->value &&
                    $donationOrderData->isExtra === true;
            });

            if(count($filterResults) > 0) {
                $customAmount = $stripePrice->unit_amount / 100 * $productOrdered->getQuantity() + current($filterResults)->amount;
            }
        }

        if(!is_null($productOrdered->getGiftModel())) {
            // GiftAdoption
            $giftAdoptionModel = new GiftAdoptionModel();
            $giftAdoptionModel
                ->setCustomerModel($customerModel)
                ->setLang(Language::from($setupIntent->metadata['language']))
                ->setPaymentMethod(PaymentMethod::CREDIT_CARD)
                ->setAmount($stripePrice->unit_amount / 100)
                ->setStripePaymentIntent($setupIntent)
                ->setAdoptedProduct(AdoptedProduct::from($productOrdered->getFullKey()))
                ->setQuantity($productOrdered->getQuantity())
                ->setProject($project)
                ->setSendToFriend($productOrdered->getGiftModel()->isSendToFriend())
                ->setCustomAmount($customAmount);

            do_action(CoralAdoptionActions::PENDING_GIFT_ADOPTION->value, $giftAdoptionModel);
        } else
        {
            $adoptionModel = new AdoptionModel();
            $adoptionModel
                ->setCustomerModel($customerModel)
                ->setLang(Language::from($setupIntent->metadata['language']))
                ->setPaymentMethod(PaymentMethod::CREDIT_CARD)
                ->setAmount($stripePrice->unit_amount / 100)
                ->setStripePaymentIntent($setupIntent)
                ->setAdoptedProduct(AdoptedProduct::from($productOrdered->getFullKey()))
                ->setProject($project)
                ->setQuantity($productOrdered->getQuantity())
                ->setCustomAmount($customAmount);

            if(!is_null($productOrdered->getSelfAdoptionModel()->getNames())) {
                $adoptionModel->setNames($productOrdered->getSelfAdoptionModel()->getNames());
            }

            do_action(CoralAdoptionActions::PENDING_ADOPTION->value, $adoptionModel);
        }

    }
}