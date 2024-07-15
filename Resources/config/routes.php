<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('glide_asset', '/media/glide/{path}')
        ->controller('DahRomy\Glide\Controller\GlideController::serveImage')
        ->requirements(['path' => '.+'])
        ->methods([Request::METHOD_GET]);
};
