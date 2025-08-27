<?php
namespace App\Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CorsMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if ($request->getMethod() === 'OPTIONS') {
            $response = new \Slim\Psr7\Response();
        } else {
            $response = $handler->handle($request);
        }

        $allowedOrigin = $this->getAllowedOrigin();

        return $response
            ->withHeader('Access-Control-Allow-Origin', $allowedOrigin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }

    private function getAllowedOrigin(): string
    {
      $devOrigin = getenv('APP_ENV');

        if ($devOrigin == 'develop') {
            return 'http://localhost:5173';
        }

        return 'https://blue-river-004e4ee03.1.azurestaticapps.net';// curl -X OPTIONS -H "Origin: http://localhost:5173" -v http://127.0.0.1:8080/api/projects*   Trying 127.0.0.1:8080...
     //   return 'http://localhost:5173';
      }
}
