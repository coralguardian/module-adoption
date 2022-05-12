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

use Hyperion\Stripe\Enum\StripeEventEnum;

add_action('plugins_loaded', 'D4rk0snet\Adoption\Plugin::launchActions');
add_action(StripeEventEnum::PAYMENT_SUCCESS->value,'\D4rk0snet\Adoption\Action\PaymentSuccessAction::doAction',10,1);
add_filter(\Hyperion\Doctrine\Plugin::ADD_ENTITIES_FILTER, function(array $entityPaths)
{
    $entityPaths[] = __DIR__."/src/Entity";

    return $entityPaths;
});