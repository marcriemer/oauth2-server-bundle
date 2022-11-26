<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('oauth2_authorize', '/authorize')
        ->controller(['league.oauth2_server.controller.authorization', 'indexAction'])

        ->add('oauth2_token', '/token')
        ->controller(['league.oauth2_server.controller.token', 'indexAction'])
        ->methods(['POST'])

        ->add('oauth2_configuration', '/.well-known/openid-configuration')
        ->controller(['league.oauth2_server.controller.openid', 'configurationAction'])
        ->methods(['GET'])

        ->add('oauth2_userinfo', '/oauth2/userinfo')
        ->controller(['league.oauth2_server.controller.openid', 'userinfoAction'])
        ->methods(['GET'])

        ->add('oauth2_revoke', '/oauth2/revoke')
        ->controller(['league.oauth2_server.controller.openid', 'revokeAction'])
        ->methods(['POST'])

        ->add('oauth2_certs', '/oauth2/certs')
        ->controller(['league.oauth2_server.controller.openid', 'certsAction'])
        ->methods(['GET'])

        ->add('oauth2_checksession', '/oauth2/checksession')
        ->controller(['league.oauth2_server.controller.openid', 'checkSessionAction'])
        ->methods(['GET'])

    ;
};
