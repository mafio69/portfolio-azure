<?php

declare(strict_types=1);

use App\Action\ListProjectsAction;
use App\Action\ContactAction;
use Slim\App;

return function (App $app) {
    $app->get('/', function ($request, $response, $args) {
        $response->getBody()->write('Backend API is running');
        return $response->withHeader('Content-Type', 'text/plain');
    });
    
    $app->get('/api/projects', ListProjectsAction::class);
    $app->post('/api/contact', ContactAction::class);
    $app->post('/api/debug', function ($request, $response) {
        $data = [
            'method' => $request->getMethod(),
            'raw_body' => $request->getBody()->getContents()
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
