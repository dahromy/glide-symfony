<?php

namespace DahRomy\Glide\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class DahRomyGlideExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../Resources/config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('dahromy_glide.config', $config);
        $container->setParameter('dahromy_glide.base_url', $config['base_url']);
        $container->setParameter('dahromy_glide.signature_key', $config['signature_key']);
        $container->setParameter('dahromy_glide.source', $config['source']);
        $container->setParameter('dahromy_glide.cache', $config['cache']);
    }
}
