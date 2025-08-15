<?php

declare(strict_types=1);

use App\Domain\Project\ProjectRepositoryInterface;
use App\Infrastructure\Persistence\Project\InMemoryProjectRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ProjectRepositoryInterface::class => \DI\autowire(InMemoryProjectRepository::class),
    ]);
};
