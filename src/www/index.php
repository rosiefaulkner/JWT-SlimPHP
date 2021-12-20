<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

/*
 * Add Error Handling Middleware
 *
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.

 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

/**
 * Middlewares
 */
// Parse json, form data and xml
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addMiddleware(new \Tuupola\Middleware\JwtAuthentication([
    "secret" => '123456789123456789000000',
    "algorithm" => ["HS256", "HS384"]
]));

// Define app routes
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello world!');
    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name=$args['name'];
    $response->getBody()->write("Hello $name!");
    return $response;
});


// Run app
$app->run();

