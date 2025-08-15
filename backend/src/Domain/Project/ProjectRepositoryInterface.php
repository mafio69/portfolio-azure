<?php

declare(strict_types=1);

namespace App\Domain\Project;

interface ProjectRepositoryInterface
{
    /**
     * @return array
     */
    public function findAll(): array;
}
