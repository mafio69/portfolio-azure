<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function ($app) {
    $app->options('/{routes:.+}', function (Request $request, Response $response, $args) {
        return $response;
    });

    $app->get('/api/projects', function (Request $request, Response $response, $args) {
        $data = [
            ['id' => 1, 'name' => 'Projekt 1', 'description' => 'Opis 1', 'url' => 'http://example.com', 'technologies' => ['PHP', 'Vue.js']],
            ['id' => 2, 'name' => 'Projekt 2', 'description' => 'Opis 2', 'url' => 'http://example2.com', 'technologies' => ['JavaScript', 'Node.js']]
        ];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
