<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Persistence\Project\InMemoryProjectRepository;

return function ($app) {
    // OPTIONS dla CORS
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    // API endpoint - używa Repository Pattern
    $app->get('/api/projects', function (Request $request, Response $response) {
        // Dependency Injection - Single Responsibility
        $projectRepository = new InMemoryProjectRepository();

        // Pobierz wszystkie projekty z repository
        $projects = $projectRepository->findAll();

        // Konwersja do array (jeśli potrzebna)
        $data = array_map(function($project) {
            return [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'description' => $project->getDescription(),
                'url' => $project->getUrl(),
                'technologies' => $project->getTechnologies()
            ];
        }, $projects);

        $response->getBody()->write(json_encode($data));

        return $response->withHeader('Content-Type', 'application/json');
    });
};
