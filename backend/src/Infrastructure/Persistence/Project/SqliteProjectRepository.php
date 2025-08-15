<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Project;

use App\Domain\Project\Project;
use App\Domain\Project\ProjectRepositoryInterface;
use PDO;

class SqliteProjectRepository implements ProjectRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT id, title, description FROM projects');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $projects = [];
        foreach ($data as $row) {
            $projects[] = new Project($row['id'], $row['title'], $row['description']);
        }

        return $projects;
    }

    public function findProjectOfId(int $id): Project
    {
        // Implement if needed, for now just return a dummy project or throw an exception
        throw new \DomainException('Method not implemented yet.');
    }
}