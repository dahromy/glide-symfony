<?php

namespace DahRomy\Glide\Service;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GlideService
 *
 * This class provides functionalities to handle Glide image manipulation.
 */
class GlideService
{
    private Server $server;
    private string $signKey;

    /**
     * Constructor.
     *
     * @param array $config The Glide configuration.
     * @param Request $request The Symfony request object.
     * @param string $signKey The Glide signature key.
     */
    public function __construct(array $config, Request $request, string $signKey)
    {
        $this->server = ServerFactory::create($config);
        $this->server->setResponseFactory(new SymfonyResponseFactory($request));
        $this->signKey = $signKey;
    }

    /**
     * Gets the image response.
     *
     * @param string $path The image path.
     * @param array $params The Glide parameters.
     *
     * @return Response The image response.
     *
     * @throws NotFoundHttpException If the image is not found.
     */
    public function getImageResponse(string $path, array $params): Response
    {
        try {
            $this->validateSignature($path, $params);
            return $this->server->getImageResponse($path, $params);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Image not found', $e);
        } catch (\Exception $e) {
            throw new NotFoundHttpException('An error occurred while processing the image', $e);
        }
    }

    /**
     * Validates the Glide signature.
     *
     * @param string $path The image path.
     * @param array $params The Glide parameters.
     *
     * @throws SignatureException If the signature is invalid.
     */
    private function validateSignature(string $path, array $params): void
    {
        $signature = $this->createSignature();
        $signature->validateRequest($path, $params);
    }

    /**
     * Creates a Glide signature object.
     *
     * @return SignatureInterface The Glide signature object.
     */
    private function createSignature(): SignatureInterface
    {
        return SignatureFactory::create($this->signKey);
    }

    /**
     * Gets the presets defined in the Glide configuration.
     *
     * @return array The Glide presets.
     */
    public function getPresets(): array
    {
        return $this->server->getPresets();
    }
}
