<?php
declare(strict_types=1);
error_log("APP_ENV from PHP: " . (getenv('APP_ENV') ?: 'NULL'));
error_log("DEV_ORIGIN from PHP: " . (getenv('DEV_ORIGIN') ?: 'NULL'));
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use App\Infrastructure\Middleware\CorsMiddleware;

// 1. Autoloadery i zależności
require __DIR__ . '/vendor/autoload.php';

// 2. Kontener DI
$containerBuilder = new ContainerBuilder();
$dependencies = require __DIR__ . '/app/dependencies.php';
$app = getApp($dependencies, $containerBuilder);
$app->add(CorsMiddleware::class);
// 5. Trasy
$routes = require __DIR__ . '/app/routes.php';
$routes($app);

// 6. Uruchom
$app->run();

