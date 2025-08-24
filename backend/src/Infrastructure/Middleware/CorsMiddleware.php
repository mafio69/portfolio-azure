<?php

declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class CorsMiddleware implements MiddlewareInterface
{
    /** @var string[] */
    private array $allowedOrigins;

    public function __construct(array $allowedOrigins = [])
    {
        // Podaj domenę SWA (produkcyjną) + localhost dla dev
        $this->allowedOrigins = $allowedOrigins ?: [
            'http://localhost:5173',
            'https://twoja-nazwa-swa.azurestaticapps.net', // TODO: wstaw własną domenę SWA
        ];
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $origin = $request->getHeaderLine('Origin');
        $allowOrigin = $this->resolveAllowedOrigin($origin);

        // Preflight: zwróć od razu 204 z nagłówkami
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            $response = (new ResponseFactory())->createResponse(204);
            return $this->withCorsHeaders($response, $allowOrigin);
        }

        // Normalne żądanie
        $response = $handler->handle($request);
        return $this->withCorsHeaders($response, $allowOrigin);
    }

    private function resolveAllowedOrigin(?string $origin): string
    {
        if ($origin && in_array($origin, $this->allowedOrigins, true)) {
            return $origin;
        }
        // Jeżeli nie rozpoznano originu, zwróć domyślnie produkcyjną domenę SWA
        // (lub pusty string, jeśli wolisz odrzucać niedozwolone po stronie przeglądarki)
        return 'https://twoja-nazwa-swa.azurestaticapps.net'; // TODO: wstaw własną domenę SWA
    }

    private function withCorsHeaders(ResponseInterface $response, string $allowOrigin): ResponseInterface
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', $allowOrigin)
            ->withHeader('Vary', 'Origin') // ważne dla cache’ów/CDN
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Max-Age', '86400');
    }
}
