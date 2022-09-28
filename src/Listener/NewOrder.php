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

        if($setupIntent->metadata['sendToFriend'] !== null) {
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
                ->setSendToFriend($setupIntent->metadata['sendToFriend'] === "true");

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
                ->setQuantity($productOrdered->getQuantity());

            do_action(CoralAdoptionActions::PENDING_ADOPTION->value, $adoptionModel);
        }

    }
}