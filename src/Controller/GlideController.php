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

    public function serveImage(Request $request, string $path): Response
    {
        if (empty($path)) {
            throw new BadRequestHttpException('Image path cannot be empty');
        }

        $params = $request->query->all();
        $validatedParams = $this->glideService->validateParams($params);
        return $this->glideService->getImageResponse($path, $validatedParams, $request);
    }
}
