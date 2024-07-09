<?php

namespace DahRomy\Glide;

use DahRomy\Glide\DependencyInjection\DahRomyGlideExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DahRomyGlideBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new DahRomyGlideExtension();
    }

    /**
     * @return mixed
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }
}
