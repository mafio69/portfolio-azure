<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Psr\Log\LoggerInterface;


require __DIR__ . '/../vendor/autoload.php';

// Create DI container builder
$containerBuilder = new ContainerBuilder();

// Add dependency definitions
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Build DI container
$container = $containerBuilder->build();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// Get error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Error handler is already configured with logger in ErrorMiddleware

$app->run();
