<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\App;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

// Domain
use App\Domain\Project\ProjectRepositoryInterface;
use App\Infrastructure\Persistence\Project\InMemoryProjectRepository;

// Middleware
use App\Infrastructure\Middleware\CorsMiddleware;

// Autoload
require __DIR__ . '/vendor/autoload.php';

/**
 * Tworzy i konfiguruje kontener DI.
 */
$buildContainer = function (): \Psr\Container\ContainerInterface {
    $containerBuilder = new ContainerBuilder();

    // Definicje usług
    $containerBuilder->addDefinitions([
        // Repozytorium projektów (na start: in-memory)
        ProjectRepositoryInterface::class => \DI\autowire(InMemoryProjectRepository::class),

        // Logger
        LoggerInterface::class => function (): LoggerInterface {
            $logger = new Logger('app');

            $logDir = __DIR__ . '/var/logs';
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

        // CORS z białą listą originów z ENV
        CorsMiddleware::class => function (): CorsMiddleware {
            $dev = getenv('DEV_ORIGIN') ?: 'http://localhost:5173';
            $prod = getenv('SWA_ORIGIN') ?: 'https://twoja-nazwa-swa.azurestaticapps.net';
            return new CorsMiddleware([$dev, $prod]);
        },
    ]);

    return $containerBuilder->build();
};

/**
 * Rejestruje trasy aplikacji (osobny plik jeśli wolisz).
 */
$registerRoutes = function (App $app): void {
    /** @var \Slim\App $app */
    $app->get('/projects', function ($request, $response) {
        $data = [
            ['id' => 1, 'name' => 'Projekt A', 'description' => 'Opis projektu A', 'url' => '#', 'technologies' => ['PHP', 'Slim']],
            ['id' => 2, 'name' => 'Projekt B', 'description' => 'Opis projektu B', 'url' => '#', 'technologies' => ['Vue', 'Vite']],
        ];
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });
};

/**
 * Buduje aplikację Slim z middleware i trasami.
 */
$container = $buildContainer();
AppFactory::setContainer($container);
$app = AppFactory::create();

// Kolejność middleware (ważne!):
// 1) CORS – globalnie, aby preflight nie trafiał w router bez potrzeby
$app->add(CorsMiddleware::class);

// 2) Routing middleware
$app->addRoutingMiddleware();

// 3) Error middleware (opcjonalnie w prod wyłącz szczegóły)
$displayErrorDetails = (bool) (getenv('APP_DEBUG') ?: '1');
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

// Rejestr tras
$registerRoutes($app);

// Zwracamy instancję aplikacji – konsument (public/index.php lub Functions handler) wywoła $app->handle(...)
return $app;
