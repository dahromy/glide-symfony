<?php

namespace DahRomy\Glide\Twig;

use Closure;
use DahRomy\Glide\Service\GlideService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GlideExtension extends AbstractExtension
{
    private GlideService $glideService;

    public function __construct(GlideService $glideService)
    {
        $this->glideService = $glideService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('glide', Closure::fromCallable([$this, 'glideFilter']), ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('glide_asset', Closure::fromCallable([$this, 'glideAssetFunction']), ['is_safe' => ['html']]),
        ];
    }

    /**
     * Generate a Glide asset URL.
     *
     * @param string $path The path to the image.
     * @param array $params An optional array of parameters to apply to the glide filter.
     * @param string|null $preset An optional preset to apply to the glide filter.
     * @return string The generated image URL.
     */
    public function glideAssetFunction(string $path, array $params = [], string $preset = null): string
    {
        return $this->glideFilter($path, $params, $preset);
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
        $allParams = array_merge(
            $preset ? $this->glideService->getPresetParams($preset) : [],
            $params
        );

        return $this->generateSignedImageUrl(ltrim($path, '/'), $allParams);
    }

    /**
     * Generates an image URL based on given parameters.
     *
     * @param array $params The parameters for generating the URL.
     *
     * @return string The generated image URL.
     */
    private function generateSignedImageUrl($path, array $params): string
    {
        $params = $this->glideService->validateParams($params);
        $signedParams = $this->glideService->generateSignedParams($path, $params);

        return $this->glideService->getImageUrl($path, $signedParams);
    }
}
