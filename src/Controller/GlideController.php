<?php

namespace DahRomy\Glide\Controller;

use DahRomy\Glide\Service\GlideService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
    public function serveImage(Request $request, string $path): Response
    {
        if (empty($path)) {
            throw new BadRequestHttpException('Image path cannot be empty');
        }

        $params = $request->query->all();
        return $this->glideService->getImageResponse($path, $params);
    }
}
