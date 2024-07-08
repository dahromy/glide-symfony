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

        // Copie du fichier de configuration par défaut si nécessaire
        $configDir = $container->getParameter('kernel.project_dir') . '/config/packages';
        $configFile = $configDir . '/dahromy_glide.yaml';

        if (!file_exists($configFile)) {
            if (!is_dir($configDir)) {
                mkdir($configDir, 0755, true);
            }
            copy(__DIR__ . '/../../Resources/config/default_config/dahromy_glide.yaml', $configFile);
        }
    }
}
