<?php

declare(strict_types=1);

namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

final class ContactAction
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            // Get JSON data from request body
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            
            // Validate required fields
            if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
                $errorResponse = [
                    'error' => true,
                    'message' => 'Wszystkie pola są wymagane'
                ];
                $response->getBody()->write(json_encode($errorResponse));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }
            
            // Basic email validation
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errorResponse = [
                    'error' => true,
                    'message' => 'Nieprawidłowy format adresu email'
                ];
                $response->getBody()->write(json_encode($errorResponse));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }
            
            // Sanitize input data
            $contactData = [
                'name' => trim(strip_tags($data['name'])),
                'email' => trim(strtolower($data['email'])),
                'message' => trim(strip_tags($data['message'])),
                'timestamp' => date('Y-m-d H:i:s'),
                'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown'
            ];
            
            // Log the contact form submission
            $this->logger->info('Contact form submission received', [
                'name' => $contactData['name'],
                'email' => $contactData['email'],
                'message_length' => strlen($contactData['message']),
                'timestamp' => $contactData['timestamp'],
                'ip' => $contactData['ip']
            ]);
            
            // In a real application, you would:
            // - Save to database
            // - Send email notification
            // - Queue for processing
            // For now, we'll just return success
            
            $successResponse = [
                'success' => true,
                'message' => 'Dziękujemy za wiadomość! Skontaktujemy się z Tobą wkrótce.',
                'timestamp' => $contactData['timestamp']
            ];
            
            $response->getBody()->write(json_encode($successResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
                
        } catch (\Exception $e) {
            $this->logger->error('Error processing contact form', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorResponse = [
                'error' => true,
                'message' => 'Wystąpił błąd serwera. Spróbuj ponownie później.'
            ];
            
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}