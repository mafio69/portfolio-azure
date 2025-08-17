<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Project\ProjectRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListProjectsAction
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $projects = $this->projectRepository->findAll();
        $response->getBody()->write(json_encode($projects));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
