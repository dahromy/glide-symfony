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
    private SymfonyResponseFactory $responseFactory;
    private string $signKey;

    public function __construct(array $config, Request $request, string $signKey)
    {
        $this->server = ServerFactory::create($config);
        $this->responseFactory = new SymfonyResponseFactory($request);
        $this->signKey = $signKey;
    }

    public function getImageResponse(string $path, array $params): Response
    {
        try {
            $this->validateSignature($path, $params);
            $processedPath = $this->getProcessedPath($path);
            return $this->responseFactory->create($this->server->getCache(), $processedPath);
        } catch (\Exception $e) {
            throw new NotFoundHttpException('Image not found', $e);
        }
    }

    /**
     * @throws SignatureException
     */
    private function validateSignature(string $path, array $params): void
    {
        $signature = $this->createSignature();
        $signature->validateRequest($path, $params);
    }

    private function createSignature(): SignatureInterface
    {
        return SignatureFactory::create($this->signKey);
    }

    /**
     * @throws FileNotFoundException
     */
    private function getProcessedPath(string $path): string
    {
        return $this->server->getSourcePath($path);
    }

    public function getPresets(): array
    {
        return $this->server->getPresets();
    }
}