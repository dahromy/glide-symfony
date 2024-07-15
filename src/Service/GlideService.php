<?php

namespace DahRomy\Glide\Service;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Server;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GlideService
{
    private Server $server;
    private UrlGeneratorInterface $urlGenerator;
    private string $signatureKey;

    public function __construct(array $config, string $signatureKey, UrlGeneratorInterface $urlGenerator)
    {
        $this->server = ServerFactory::create($config);
        $this->urlGenerator = $urlGenerator;
        $this->signatureKey = $signatureKey;
    }

    public function getImageResponse(string $path, array $params, Request $request): Response
    {
        try {
            $this->validateSignature($path, $params);
            return $this->server->getImageResponse($path, $params);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Image not found', $e);
        } catch (SignatureException $e) {
            throw new AccessDeniedHttpException('Invalid image signature', $e);
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Error processing the image', $e);
        }
    }

    public function getImageUrl(string $path, array $params = []): string
    {
        $params = $this->generateSignedParams($path, $params);
        return $this->urlGenerator->generate('dahromy_glide_serve', array_merge(['path' => $path], $params));
    }

    public function validateParams(array $params): array
    {
        return $this->server->getApi()->validateParams($params);
    }

    /**
     * @throws SignatureException
     */
    private function validateSignature(string $path, array $params): void
    {
        SignatureFactory::create($this->signatureKey)->validateRequest($path, $params);
    }

    public function generateSignedParams(string $path, array $params = []): array
    {
        return SignatureFactory::create($this->signatureKey)->addSignature($path, $params);
    }

    public function getPresets(): array
    {
        return $this->server->getPresets();
    }

    public function getPresetParams(string $preset): array
    {
        $presets = $this->getPresets();
        return $presets[$preset] ?? [];
    }
}
