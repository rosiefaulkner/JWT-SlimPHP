<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require('../inc/bootstrap.php');

//Load env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

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
// Define app routes
$app->add(CorsMiddleware::class);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$container = $app->getContainer();
$rawPublicKeys = file_get_contents('https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-odgy7%40wf-api-ccdfe.iam.gserviceaccount.com');
$keys = json_decode($rawPublicKeys, true);
$app->add(new \Tuupola\Middleware\JwtAuthentication([
	"algorithm" => ["HS256"],
	"header" => "X-Authorization",
	"secret" => $keys,
	"secure" => false,
	"error" => function ($response, $arguments) {
		$data["status"] = "error";
		$data["message"] = $arguments["message"];
		return $response
			->withHeader("Content-Type", "application/json")
			->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	},
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    }
]));

// Run app
$app->run();
