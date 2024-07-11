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
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../Resources/config'));
        $loader->load('services.php');

        $container->setParameter('dahromy_glide.config', $config);
        $container->setParameter('dahromy_glide.signature_key', '%env(GLIDE_SIGNATURE_KEY)%');
    }

    public function getAlias(): string
    {
        return 'dahromy_glide';
    }
}
