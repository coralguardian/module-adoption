<?php

namespace D4rk0snet\Adoption;

use D4rk0snet\Adoption\API\AddFriendToGiftAdoption;
use D4rk0snet\Adoption\API\AdoptionEndpoint;
use D4rk0snet\Adoption\API\GetAdoptionForRedirection;
use D4rk0snet\Adoption\API\GiftAdoptionEndpoint;
use D4rk0snet\Adoption\API\NameAdopteesEndpoint;

class Plugin
{
    public static function launchActions()
    {
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new AdoptionEndpoint());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new NameAdopteesEndpoint());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new GiftAdoptionEndpoint());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new AddFriendToGiftAdoption());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new GetAdoptionForRedirection());
    }
}