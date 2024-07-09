<?php


use DahRomy\Glide\Controller\GlideController;
use DahRomy\Glide\Service\GlideService;
use DahRomy\Glide\Twig\Extension\GlideExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(GlideService::class)
        ->args(['%dahromy_glide.config%', service('request_stack'), '%dahromy_glide.signature_key%']);

    $services->set(GlideExtension::class)
        ->args([
            service('router'),
            service(GlideService::class),
            '%dahromy_glide.base_url%'
        ])
        ->tag('twig.extension');

    $services->set(GlideController::class)
        ->args([service(GlideService::class)])
        ->tag('controller.service_arguments');
};
