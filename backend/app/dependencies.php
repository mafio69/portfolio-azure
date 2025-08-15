<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use App\Domain\Project\ProjectRepositoryInterface;
use App\Infrastructure\Persistence\Project\InMemoryProjectRepository;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ProjectRepositoryInterface::class => \DI\autowire(InMemoryProjectRepository::class),

        LoggerInterface::class => function (ContainerInterface $container) {
            $logger = new Logger('app');

            $formatter = new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                "Y-m-d H:i:s",
                false,
                true
            );

            $streamHandler = new StreamHandler(__DIR__ . '/../var/logs/app.log', Logger::DEBUG);
            $streamHandler->setFormatter($formatter);

            $logger->pushHandler($streamHandler);

            return $logger;
        },
    ]);
};
