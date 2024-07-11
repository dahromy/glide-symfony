<?php

namespace DahRomy\Glide\Controller;

use DahRomy\Glide\Service\GlideService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GlideController
{
    private GlideService $glideService;

    public function __construct(GlideService $glideService)
    {
        $this->glideService = $glideService;
    }

    /**
     * Retrieves the asset image using the Glide service.
     *
     * @param string $path The path of the asset image.
     * @param Request $request The request instance.
     * @return Response The response containing the asset image.
     */
    public function asset(string $path, Request $request): Response
    {
        return $this->glideService->getImageResponse($path, $request->query->all());
    }
}
