<?php

namespace D4rk0snet\Adoption;

use D4rk0snet\Adoption\API\IndividualAdoptionEndpoint;
use Hyperion\Stripe\Enum\WordpressEventEnum;

class Plugin
{
    public static function init()
    {
        add_action(WordpressEventEnum::PAYMENT_SUCCESS->value,'\D4rk0snet\Adoption\Action\PaymentSuccessAction::doAction',10,1);
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new IndividualAdoptionEndpoint());
        add_filter(\Hyperion\Doctrine\Plugin::ADD_ENTITIES_FILTER, function(array $entityPaths)
        {
            $entityPaths[] = __DIR__."/Entity";

            return $entityPaths;
        });
    }
}