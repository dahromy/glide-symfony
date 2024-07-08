<?php

namespace DahRomy\Glide\Twig\Extension;

use DahRomy\Glide\Twig\Runtime\GlideExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GlideExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('glide', [GlideExtensionRuntime::class, 'glideFilter'], ['is_safe' => ['html']]),
        ];
    }
}
