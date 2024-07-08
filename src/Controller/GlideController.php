<?php

namespace DahRomy\Glide\Controller;

use DahRomy\Glide\Service\GlideService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GlideController
{
    public function __construct(
        private GlideService $glideService
    )
    {
    }

    /**
     * Retrieves the asset image using the Glide service.
     *
     * @Route("/glide/{path<.+>}", name="dahromy_glide_asset", methods={"GET"})
     *
     * @param string $path The path of the asset image.
     * @param Request $request The request instance.
     * @return Response The response containing the asset image.
     */
    #[Route('/glide/{path<.+>}', name: 'dahromy_glide_asset', methods: ['GET'])]
    public function asset(string $path, Request $request): Response
    {
        $parameters = $request->query->all();
        return $this->glideService->getImageResponse($path, $parameters);
    }
}
