<?php

namespace D4rk0snet\Adoption\Listener\Action;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use D4rk0snet\CoralOrder\Model\OrderModel;

/**
 * Cette classe écoute l'action NEW_ORDER du module order
 */
class NewOrderListener
{
    public static function doAction(OrderModel $model)
    {
        if(count($model->getProductsOrdered()) === 0) {
            return;
        }

        // On a des adoptions, on les entre en base
        foreach($model->getProductsOrdered() as $product) {
            if($model->getGiftModel() === null) {
                $adoptionModel = new AdoptionModel();
                $adoptionModel
                    ->setQuantity($product->getQuantity())
                    ->setPaymentMethod(PaymentMethod::CREDIT_CARD)
                    ->setLang($model->getLang())
                    ->setCustomerModel($model->getCustomer())
                    ->setAdoptedProduct(AdoptedProduct::from($product->getKey()))
                    ->setAmount($model->getTotalAmount());

                do_action(CoralAdoptionActions::PENDING_ADOPTION, $adoptionModel);
            }

            $adoptionModel = new GiftAdoptionModel();
            $adoptionModel
                ->setQuantity($product->getQuantity())
                ->setPaymentMethod(PaymentMethod::CREDIT_CARD)
                ->setLang($model->getLang())
                ->setCustomerModel($model->getCustomer())
                ->setAdoptedProduct(AdoptedProduct::from($product->getKey()))
                ->setAmount($model->getTotalAmount())
                ->setMessage($model->getGiftModel()->getMessage())
                ->setSendOn($model->getGiftModel()->getSendOn())
                ->setSendToFriend($model->getGiftModel()->isSendToFriend())
                ->setFriends($model->getGiftModel()->getFriends());

            do_action(CoralAdoptionActions::PENDING_GIFT_ADOPTION, $adoptionModel);
        }
    }
}