<?php

namespace DahRomy\Glide\Service;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class GlideService
 *
 * This class provides functionality to handle Glide image manipulation.
 */
class GlideService
{
    private Server $server;
    private string $signKey;
    private SignatureInterface $signature;

    /**
     * Constructor.
     *
     * @param array $config The Glide configuration.
     * @param RequestStack $requestStack
     * @param string $signKey The Glide signature key.
     * @param SignatureInterface $signature
     */
    public function __construct(array $config, RequestStack $requestStack, string $signKey, SignatureInterface $signature)
    {
        $request = $requestStack->getCurrentRequest();

        $this->server = ServerFactory::create($config);

        $this->server->setResponseFactory(new SymfonyResponseFactory($request));
        $this->signKey = $signKey;
        $this->signature = $signature;
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
        } catch (SignatureException $e) {
            throw new AccessDeniedHttpException('Invalid image signature', $e);
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while processing the image', $e);
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
        $this->signature->validateRequest($path, $params);
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

    public function generateSignedParams(string $path, array $params = []): array
    {
        return $this->signature->addSignature($path, $params);
    }

    public function normalizeParams(array $params = []): array
    {
        return $this->server->getAllParams($params);
    }
}
