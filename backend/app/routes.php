<?php

declare(strict_types=1);

use App\Action\ListProjectsAction;
use Slim\App;

return function (App $app) {
    $app->get('/', function ($request, $response, $args) {
        $response->getBody()->write('Backend API is running');
        return $response->withHeader('Content-Type', 'text/plain');
    });
    
    $app->get('/api/projects', ListProjectsAction::class);
};
