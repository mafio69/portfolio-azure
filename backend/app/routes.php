<?php
// Obsługa preflight OPTIONS requests
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

// Twoje istniejące trasy...
$app->get('/api/projects', function ($request, $response) {
    $data = [
        ['id' => 1, 'name' => 'Projekt 1', 'description' => 'Opis 1', 'url' => 'http://example.com', 'technologies' => ['PHP', 'Vue.js']],
        ['id' => 2, 'name' => 'Projekt 2', 'description' => 'Opis 2', 'url' => 'http://example2.com', 'technologies' => ['JavaScript', 'Node.js']]
    ];

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->getBody()->write(json_encode($data));
});
