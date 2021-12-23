<?php
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Slim\Factory\AppFactory;

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
class Api
{
	//vars
	private $key = 'privatekey';

	public function __construct()
	{
		$this->auth();
	}
	//Define middleware
	public function middleware()
	{
		$app = AppFactory::create();
		$app->addMiddleware(new \Tuupola\Middleware\JwtAuthentication([
			"secret" => $this->key,
			"secure" => true,
			"relaxed" => ["localhost", "http://localhost:8888/"]
		]));
		return $app->addErrorMiddleware(true, true, true);
	}
	public function auth()
	{
		$this->middleware();
		$iat = time();
		$exp = $iat + 60 * 60;
		$payload = [
			'iss' => 'http://localhost:8888', //issuer
			'aud' => 'http://localhost:8888/', //audience
			'iat' => $iat, //time jwt was issued
			'exp' => $exp //time jwt expires
		];
		$jwt = JWT::encode($payload, $this->key);
		$decoded = JWT::decode($jwt, $this->key, ['HS256']);
		$decoded_array = (array)$decoded;
		JWT::$leeway = 60; // $leeway in seconds
		return [
			'token' => $jwt,
			'expires' => $exp
		];
	}
	// Define app routes
	public function routes()
	{
		$app = AppFactory::create();
		if($this->auth()){
			$app->get('/', function (Response $response) {
				$response->getBody()->write(json_encode($this->auth()));
				echo $response
				->withHeader('Content-Type', 'application/json')
				->withHeader('Content-Type', 'charset=UTF-8')
				->withStatus(200);
			});
		}else{
			http_response_code(404);
			echo json_encode([
				'type' => 'danger',
				'title' => 'Failed',
				'message' => 'Could not create token for this API. Please contact Rosie.'
			]);
		}
	}
}
$api = new Api();
