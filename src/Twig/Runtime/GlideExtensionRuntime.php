<?php

namespace DahRomy\Glide\Twig\Runtime;

use DahRomy\Glide\Service\GlideService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GlideExtensionRuntime
{
    private UrlGeneratorInterface $router;
    private GlideService $glideService;
    private string $baseUrl;

    public function __construct(
        UrlGeneratorInterface $router,
        GlideService $glideService,
        string $baseUrl
    ) {
        $this->router = $router;
        $this->glideService = $glideService;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Apply glide filter to the given path and return the generated image URL.
     *
     * @param string $path The path to the image.
     * @param array $params An optional array of parameters to apply to the glide filter.
     * @param string|null $preset An optional preset to apply to the glide filter.
     * @return string The generated image URL.
     */
    public function glideFilter(string $path, array $params = [], string $preset = null): string
    {
        if ($preset) {
            $params = array_merge($this->glideService->getPresets()[$preset] ?? [], $params);
        }
        $params['path'] = ltrim($path, '/');
        return $this->generateImageUrl($params);
    }

    /**
     * Generates an image URL based on given parameters.
     *
     * @param array $params The parameters for generating the URL.
     *
     * @return string The generated image URL.
     */
    private function generateImageUrl(array $params): string
    {
        return $this->baseUrl . $this->router->generate('dahromy_glide_asset', $params);
    }
}
