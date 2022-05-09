<?php

namespace D4rk0snet\Adoption;

class Plugin
{
    public static function init()
    {
        add_action('payment_success','\D4rk0snet\Adoption\Action\PaymentSuccessAction::doAction',10,1);
    }
}