<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use App\Domain\Project\ProjectRepositoryInterface;
use App\Infrastructure\Persistence\Project\InMemoryProjectRepository;
use App\Infrastructure\Middleware\CorsMiddleware;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        // Repository
        ProjectRepositoryInterface::class => \DI\autowire(InMemoryProjectRepository::class),

        // Logger
        LoggerInterface::class => function (): LoggerInterface {
            $logger = new Logger('app');
            $logDir = __DIR__ . '/../var/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }

            $formatter = new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                "Y-m-d H:i:s",
                false,
                true
            );

            $streamHandler = new StreamHandler($logDir . '/app.log', Logger::DEBUG);
            $streamHandler->setFormatter($formatter);
            $logger->pushHandler($streamHandler);
            return $logger;
        },

        // CORS z ENV-based origin whitelist
        CorsMiddleware::class => function (): CorsMiddleware {
            $dev = getenv('DEV_ORIGIN') ?: 'http://localhost:5173';
            $prod = getenv('SWA_ORIGIN') ?: 'https://twoja-nazwa-swa.azurestaticapps.net';
            return new CorsMiddleware([$dev, $prod]);
        },
    ]);
};
