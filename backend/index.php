<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use App\Infrastructure\Middleware\CorsMiddleware;

// 1. Autoloadery i zależności
require __DIR__ . '/vendor/autoload.php';

// 2. Kontener DI
$containerBuilder = new ContainerBuilder();
$dependencies = require __DIR__ . '/app/dependencies.php';
$dependencies($containerBuilder);
$container = $containerBuilder->build();

// 3. Aplikacja Slim
AppFactory::setContainer($container);
$app = AppFactory::create();

// 4. Middleware
$app->add(CorsMiddleware::class);
$app->addRoutingMiddleware();
$displayErrorDetails = (bool) (getenv('APP_DEBUG') ?: '1');
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

// 5. Trasy
$routes = require __DIR__ . '/app/routes.php';
$routes($app);

// 6. Uruchom
$app->run();

