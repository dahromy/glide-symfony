<?php

namespace DahRomy\Glide\Service;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class GlideService
 *
 * This class provides functionality to handle Glide image manipulation.
 */
class GlideService
{
    private Server $server;
    private SignatureInterface $signature;
    private UrlGeneratorInterface $router;
    private array $presets = [];

    /**
     * Constructor.
     *
     * @param array $config The Glide configuration.
     * @param string $signKey The Glide signature key.
     * @param RequestStack $requestStack
     * @param SignatureInterface $signature
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        array $config,
        string $signKey,
        RequestStack $requestStack,
        SignatureInterface $signature,
        UrlGeneratorInterface $router
    )
    {
        $request = $requestStack->getCurrentRequest();

        $this->server = ServerFactory::create($config);
        $this->signature = $signature;
        $this->server->setResponseFactory(new SymfonyResponseFactory($request));
        $this->router = $router;
        $this->presets = $config['presets'] ?? [];
    }

    public function getImageUrl(string $path, array $params): string
    {
        $params['path'] = $path;
        return $this->router->generate('dahromy_glide_asset', $params);
    }

    public function getPresetParams(string $preset): array
    {
        return $this->presets[$preset] ?? [];
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

    public function validateParams(array $params): array
    {
        $validParams = [];
        foreach ($params as $key => $value) {
            if ($this->isValidGlideParameter($key, $value)) {
                $validParams[$key] = $value;
            }
        }
        return $validParams;
    }

    private function isValidGlideParameter(string $key, $value): bool
    {
        $allowedParams = ['or', 'flip', 'crop', 'w', 'h', 'fit', 'dpr', 'bri', 'con', 'gam', 'sharp', 'blur', 'pixel', 'filt', 'mark', 'markw', 'markh', 'markx', 'marky', 'markpad', 'markpos', 'markalpha', 'bg', 'border', 'q', 'fm'];
        return in_array($key, $allowedParams);
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
