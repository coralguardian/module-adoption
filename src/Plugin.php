<?php

namespace D4rk0snet\Adoption;

use Hyperion\Stripe\Enum\WordpressEventEnum;

class Plugin
{
    public static function init()
    {
        add_action(WordpressEventEnum::PAYMENT_SUCCESS->value,'\D4rk0snet\Adoption\Action\PaymentSuccessAction::doAction',10,1);
    }
}