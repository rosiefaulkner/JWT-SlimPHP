<?php

/**
 * @OA\Server(url="http://localhost:8888")
 * @OA\Info(title="wf-api", version="1.0.0")
 */

use Firebase\JWT\JWT;
use Slim\Factory\AppFactory;

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
class Api
{
	private $key = 'privatekey';
	public function __construct()
	{
		$this->middleware();
		$this->routes();
	}
	//Define middleware
	public function middleware()
	{
		$app = AppFactory::create();
		return $app->addErrorMiddleware(true, true, true);
	}
	/**
	 * @OA\Get(path="/src/www/index/routes", tags={'Api'},
	 * @OA\Response (response="200", description="Success"),
	 * @OA\Response (response="404", description="Not Found"),
	 * )
	 */
	// Define app routes
	public function routes()
	{
		$app = AppFactory::create();
		$app->get('/', function (Request $request, Response $response) {
			$response->getBody()->write('Hello world!');
			return $response;
		});
	}
	public function auth()
	{
		$iat = time();
		$exp = $iat + 60 * 60;
		$payload = [
			'iss' => 'http://localhost:8888', //issuer
			'aud' => 'http://localhost:8888/', //audience
			'iat' => $iat, //time jwt was issued
			'exp' => $exp //time jwt expires
		];
		$jwt = JWT::encode($payload, $this->key, 'HS512');
		return [
			'token' => $jwt,
			'expires' => $exp
		];
	}
}
$api = new Api();
