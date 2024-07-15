<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('dahromy_glide_asset', '/media/glide/{path}')
        ->controller('DahRomy\Glide\Controller\GlideController::serveImage')
        ->requirements(['path' => '.+'])
        ->methods([Request::METHOD_GET]);

    $routes->add('dahromy_glide_asset_with_preset', '/media/glide/{preset}/{path}')
        ->controller('DahRomy\Glide\Controller\GlideController::serveImage')
        ->requirements(['path' => '.+', 'preset' => '[a-zA-Z0-9_-]+'])
        ->methods([Request::METHOD_GET]);

    $routes->add('image_list', '/images')
        ->controller('DahRomy\Glide\Controller\ImageController::listImages')
        ->methods([Request::METHOD_GET]);
};
