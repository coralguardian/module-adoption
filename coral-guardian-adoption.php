<?php
/**
 * Plugin Name: Adopte un corail / recif
 * Plugin URI:
 * Description: Gestion de l'adoption
 * Version: 0.1
 * Requires PHP: 8.1
 * Author: Benoit DELBOE & Grégory COLLIN
 * Author URI:
 * Licence: GPLv2
 */

use D4rk0snet\Adoption\Action\CreateAdoption;
use D4rk0snet\Adoption\Action\CreateGiftAdoption;
use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Enums\CoralAdoptionFilters;
use D4rk0snet\Adoption\Filter\GetGiftAdoptionFilter;
use D4rk0snet\Adoption\Listener\Action\GiftAdoptionPaymentSuccessAction;
use D4rk0snet\Adoption\Listener\Action\NewOrderListener;
use D4rk0snet\CoralOrder\Enums\CoralOrderEvents;
use Hyperion\Doctrine\Plugin;
use Hyperion\Stripe\Enum\StripeEventEnum;

add_action('plugins_loaded', [\D4rk0snet\Adoption\Plugin::class,'launchActions']);
add_action(StripeEventEnum::PAYMENT_SUCCESS->value, [GiftAdoptionPaymentSuccessAction::class,'doAction'], 10, 1);
add_action(CoralOrderEvents::NEW_ORDER, [NewOrderListener::class, 'doAction'], 10,1);
add_action(CoralAdoptionActions::PENDING_ADOPTION, [CreateAdoption::class, 'doAction'], 10,1);
add_action(CoralAdoptionActions::PENDING_GIFT_ADOPTION, [CreateGiftAdoption::class, 'doAction'], 10,1);
add_filter(CoralAdoptionFilters::GET_GIFTADOPTION, [GetGiftAdoptionFilter::class, 'doAction']);
add_filter(Plugin::ADD_ENTITIES_FILTER, function (array $entityPaths) {
    $entityPaths[] = __DIR__."/src/Entity";

    return $entityPaths;
});
