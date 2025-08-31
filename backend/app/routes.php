<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Action\ListProjectsAction;

return function ($app) {
    // Endpoints dotyczące projektów
    $app->get('/api/projects', ListProjectsAction::class);

    // Tu możesz dodać kolejne trasy, np.:
    // $app->get('/api/inna_trasa', InnaAkcja::class);

    // Przykładowy endpoint zdrowotny (nieobowiązkowy)
    $app->get('/api/health', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['status' => 'OK']));

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/', function (Request $request, Response $response) {
        $html = <<<HTML
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Tryb awaryjny - Portfolio</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333; text-align: center; padding: 50px; }
        h1 { color: #d9534f; }
        p { font-size: 1.2em; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tryb awaryjny Portfolio</h1>
        <p>Aktualnie strona jest niedostępna z powodu prac konserwacyjnych lub awarii.</p>
        <p>Pracujemy nad tym, aby jak najszybciej przywrócić pełną funkcjonalność. Prosimy o cierpliwość i zapraszamy wkrótce!</p>
    </div>
</body>
</html>
HTML;

        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    });
};

