<?php

declare(strict_types=1);

use App\Action\ListProjectsAction;
use Slim\App;

return function (App $app) {
    $app->get('/api/projects', ListProjectsAction::class);
};
