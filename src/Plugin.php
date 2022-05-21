<?php

namespace D4rk0snet\Adoption;

use D4rk0snet\Adoption\API\AdoptionEndpoint;
use D4rk0snet\Adoption\API\GiftAdoptionEndpoint;
use D4rk0snet\Adoption\API\NameAdopteesEndpoint;
use WP_CLI;

class Plugin
{
    public static function launchActions()
    {
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new AdoptionEndpoint());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new NameAdopteesEndpoint());
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new GiftAdoptionEndpoint());
    }

    public static function addCLICommands()
    {
        WP_CLI::add_command('doctrine_cli', ['\D4rk0snet\Adoption\Command\SendFutureGift','runCommand']);
    }
}