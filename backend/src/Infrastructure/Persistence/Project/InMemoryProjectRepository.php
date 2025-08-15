<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Project;

use App\Domain\Project\ProjectRepositoryInterface;

class InMemoryProjectRepository implements ProjectRepositoryInterface
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        return [
            ['title' => 'Projekt 1', 'desc' => 'Opis projektu 1'],
            ['title' => 'Projekt 2', 'desc' => 'Opis projektu 2']
        ];
    }
}
