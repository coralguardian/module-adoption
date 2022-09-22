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
use Stripe\PaymentIntent;

/**
 * Cette classe écoute l'action NEW_ORDER du module order
 */
class NewPaymentDone
{
    public static function doAction(PaymentIntent $stripePaymentIntent)
    {
        $mapper = new JsonMapper();
        $mapper->bExceptionOnMissingData = true;
        $mapper->postMappingMethod = 'afterMapping';

        // Si c'est une adoption, nous aurons le productOrder dans les metas du paymentIntent
        if($stripePaymentIntent->metadata['productOrdered'] === null) {
            return;
        }
        $customerModel = $mapper->map(json_decode($stripePaymentIntent->metadata['customer'], false, 512, JSON_THROW_ON_ERROR), new CustomerModel());
        /** @var ProductOrderModel $productOrdered */
        $productOrdered = $mapper->map(json_decode($stripePaymentIntent->metadata['productOrdered'], false, 512, JSON_THROW_ON_ERROR), new ProductOrderModel());

        // Récupère le prix pour le produit depuis stripe
        $stripeProduct = ProductService::getProduct($productOrdered->getKey(), $productOrdered->getProject(), $productOrdered->getVariant());
        $stripePrice = StripeService::getStripeClient()->prices->retrieve($stripeProduct->default_price);

        if($stripePaymentIntent->metadata['giftAdoption'] !== null) {
            // GiftAdoption
            $giftAdoptionModel = new GiftAdoptionModel();
            $giftAdoptionModel
                ->setCustomerModel($customerModel)
                ->setLang(Language::from($stripePaymentIntent->metadata['language']))
                ->setPaymentMethod(PaymentMethod::CREDIT_CARD)
                ->setAmount($stripePrice->unit_amount / 100)
                ->setStripePaymentIntent($stripePaymentIntent)
                ->setAdoptedProduct(AdoptedProduct::from($productOrdered->getFullKey()))
                ->setQuantity($productOrdered->getQuantity())
                ->setProject(Project::from($productOrdered->getProject()))
                ->setSendToFriend($stripePaymentIntent->metadata['giftAdoption']);

            do_action(CoralAdoptionActions::PENDING_GIFT_ADOPTION->value, $giftAdoptionModel);
        } else
        {
            $adoptionModel = new AdoptionModel();
            $adoptionModel
                ->setCustomerModel($customerModel)
                ->setLang(Language::from($stripePaymentIntent->metadata['language']))
                ->setPaymentMethod(PaymentMethod::CREDIT_CARD)
                ->setAmount($stripePrice->unit_amount / 100)
                ->setStripePaymentIntent($stripePaymentIntent)
                ->setAdoptedProduct(AdoptedProduct::from($productOrdered->getFullKey()))
                ->setProject(Project::from($productOrdered->getProject()))
                ->setQuantity($productOrdered->getQuantity());

            do_action(CoralAdoptionActions::PENDING_ADOPTION->value, $adoptionModel);
        }

    }
}