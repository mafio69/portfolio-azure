<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Middleware\CorsMiddleware;
use Slim\Logger;

require __DIR__ . '/vendor/autoload.php';

// 1) Kontener DI
$containerBuilder = new ContainerBuilder();

// Załaduj definicje zależności
$dependencies = require __DIR__ . '/app/dependencies.php';
/**
 * @param mixed $dependencies
 * @param ContainerBuilder $containerBuilder
 * @return App
 * @throws Exception
 */
function getApp(mixed $dependencies, ContainerBuilder $containerBuilder): App
{
    $dependencies($containerBuilder);

// (opcjonalnie) kompilacja kontenera w prod
// if (getenv('APP_ENV') === 'prod') {
//     $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
// }

    $container = $containerBuilder->build();

// 2) Aplikacja Slim
    AppFactory::setContainer($container);
    $app = AppFactory::create();
    foreach ($app->getRouteCollector()->getRoutes() as $route) {
        error_log('ROUTE: ' . $route->getPattern());

// 3) Middleware – kolejność ma znaczenie
// CORS najpierw: szybka obsługa preflight OPTIONS
    $app->add(CorsMiddleware::class);

// Routing middleware
    $app->addRoutingMiddleware();

// Error middleware
    $displayErrorDetails = (bool)(getenv('APP_DEBUG') ?: '1');
    $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

    return $app;
}

try {
    $app = getApp($dependencies, $containerBuilder);
    $routes = require __DIR__ . '/app/routes.php';
    $routes($app);
    $app->run();
} catch (Exception $e) {
    Logger::debug($e->getMessage()) . ' in '. $e->getFile() . ' on line '. $e->getLine();
}


