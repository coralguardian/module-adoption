<?php

namespace D4rk0snet\Adoption;

use D4rk0snet\Adoption\API\IndividualAdoptionEndpoint;
use D4rk0snet\Adoption\API\NameAdopteesEndpoint;

class Plugin
{
    public static function launchActions()
    {
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new IndividualAdoptionEndpoint());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new NameAdopteesEndpoint());
    }
}