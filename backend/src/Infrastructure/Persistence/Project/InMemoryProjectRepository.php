<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Project;

use App\Domain\Project\ProjectRepositoryInterface;
use App\Domain\Project\Project;

class InMemoryProjectRepository implements ProjectRepositoryInterface
{
    /**
     * @return Project[]
     */
    public function findAll(): array
    {
        return [
            new Project(1, 'Projekt 1', 'Opis projektu 1', ''),
            new Project(2, 'Projekt 2', 'Opis projektu 2', '')
        ];
    }

    /**
     * @param int $id
     * @return Project|null
     */
    public function findProjectOfId(int $id): ?Project
    {
        $projects = $this->findAll();
        foreach ($projects as $project) {
            if ($project->getId() === $id) {
                return $project;
            }
        }
        return null;
    }
}
