<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('dahromy_glide_asset', '/media/glide/{preset}/{path}')
        ->controller('DahRomy\Glide\Controller\GlideController::serveImage')
        ->requirements(['path' => '.+', 'preset' => '[a-zA-Z0-9_-]+'])
        ->defaults(['preset' => null])
        ->methods([Request::METHOD_GET]);
};
