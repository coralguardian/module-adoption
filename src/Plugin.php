<?php

namespace D4rk0snet\Adoption;

use D4rk0snet\Adoption\API\IndividualAdoptionEndpoint;
use D4rk0snet\Adoption\API\NameAdopteesEndpoint;
use Hyperion\Stripe\Enum\WordpressEventEnum;

class Plugin
{
    public static function init()
    {
        add_action(WordpressEventEnum::PAYMENT_SUCCESS->value,'\D4rk0snet\Adoption\Action\PaymentSuccessAction::doAction',10,1);
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new IndividualAdoptionEndpoint());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new NameAdopteesEndpoint());
        add_filter(\Hyperion\Doctrine\Plugin::ADD_ENTITIES_FILTER, function(array $entityPaths)
        {
            $entityPaths[] = __DIR__."/Entity";

            return $entityPaths;
        });
    }
}