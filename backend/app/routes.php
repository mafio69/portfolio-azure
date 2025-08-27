<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Action\ListProjectsAction;

return function ($app) {
    // Endpoints dotyczące projektów
    $app->get('/api/projects', ListProjectsAction::class);

    // Tu możesz dodać kolejne trasy, np.:
    // $app->get('/api/inna_trasa', InnaAkcja::class);

    // Przykładowy endpoint zdrowotny (nieobowiązkowy)
    $app->get('/api/health', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['status' => 'OK']));
        return $response->withHeader('Content-Type', 'application/json');
    });
};

