<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Middleware\CorsMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// 1) Kontener DI
$containerBuilder = new ContainerBuilder();

// Załaduj definicje zależności
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// (opcjonalnie) kompilacja kontenera w prod
// if (getenv('APP_ENV') === 'prod') {
//     $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
// }

$container = $containerBuilder->build();

// 2) Aplikacja Slim
AppFactory::setContainer($container);
$app = AppFactory::create();

// 3) Middleware – kolejność ma znaczenie
// CORS najpierw: szybka obsługa preflight OPTIONS
$app->add(CorsMiddleware::class);

// Routing middleware
$app->addRoutingMiddleware();

// Error middleware
$displayErrorDetails = (bool) (getenv('APP_DEBUG') ?: '1');
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

// 4) Trasy
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// 5) Start
$app->run();
