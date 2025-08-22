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
        return array(
            new Project(
                1,
                'Docker Start: Środowisko Deweloperskie PHP',
                'Kompletne środowisko deweloperskie oparte na Dockerze, stworzone dla nowoczesnych aplikacji PHP. Zapewnia prekonfigurowany kontener z Nginx, PHP-FPM oraz Xdebug, gotowy do uruchomienia jednym poleceniem. Idealne dla zachowania czystości i przenośności projektów.',
                'https://github.com/mafio69/startapp', // URL do repozytorium GitHub
                array('Docker', 'Docker Compose', 'PHP', 'Nginx', 'Xdebug')
            ),
            new Project(
                2,
                'Portfolio Full-Stack (Vue.js + PHP)',
                'Aplikacja portfolio, którą właśnie budujemy. Frontend w Vue.js z Vuetify i TypeScript, komunikujący się z backendem PHP opartym o Slim Framework. Backend zaprojektowany w architekturze heksagonalnej (Ports & Adapters), z naciskiem na zasady Clean Code i SOLID.',
                'https://github.com/mafio69/portfolio-azure',
                array('Vue.js', 'Vuetify', 'TypeScript', 'PHP', 'Slim Framework', 'Hexagonal Architecture')
            ),
            new Project(
                3,
                'System Kolejek Górskich (REST API)',
                'Backendowe API do zarządzania systemem kolejek górskich, oparte na frameworku CodeIgniter 4. Aplikacja uruchamiana jest w środowisku Docker, wykorzystuje Redis do obsługi kolejek i MySQL jako bazę danych. Projekt obejmuje testy jednostkowe (PHPUnit) oraz testy wydajnościowe (k6).',
                'https://github.com/mafio69/kolejki', // URL do repozytorium GitHub
                array('PHP', 'CodeIgniter 4', 'Docker', 'Redis', 'MySQL', 'k6', 'PHPUnit')
            )
        );
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
