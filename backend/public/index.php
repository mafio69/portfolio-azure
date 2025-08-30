<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Middleware\CorsMiddleware;
use Slim\Logger;

require __DIR__ . '/../vendor/autoload.php';

// 1) Kontener DI
$containerBuilder = new ContainerBuilder();

// Załaduj definicje zależności
$dependencies = require __DIR__ . '/../app/dependencies.php';
/**
 * @param mixed $dependencies
 * @param ContainerBuilder $containerBuilder
 * @return \Slim\App
 * @throws Exception
 */
function getApp(mixed $dependencies, ContainerBuilder $containerBuilder): \Slim\App
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
    $logFile = '/tmp/debug.log';
    file_put_contents($logFile, "--- NEW REQUEST ---\n", FILE_APPEND);
    file_put_contents($logFile, "Time: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    file_put_contents($logFile, "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n", FILE_APPEND);
    file_put_contents($logFile, "Script Filename: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "\n\n", FILE_APPEND);


    $app = getApp($dependencies, $containerBuilder);
    file_put_contents($logFile, "OK: Slim App created.\n", FILE_APPEND);

    $routes = require __DIR__ . '/../app/routes.php';
    file_put_contents($logFile, "OK: routes.php loaded.\n", FILE_APPEND);

    $routes($app);
    file_put_contents($logFile, "OK: Routes registered.\n", FILE_APPEND);

    $app->run();
    file_put_contents($logFile, "OK: App run completed.\n", FILE_APPEND);

} catch (Exception $e) {
    Logger::debug($e->getMessage()) . ' in '. $e->getFile() . ' on line '. $e->getLine();
}


