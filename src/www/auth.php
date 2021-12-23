<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once $_SERVER['DOCUMENT_ROOT'].'/index.php';

$token = new Api();

if($token->auth()){
	http_response_code(200);
	echo json_encode(
		$token->auth()
	);
}else{
	http_response_code(404);
	echo json_encode([
		'type' => 'danger',
		'title' => 'Failed',
		'message' => 'Could not create token for this API. Please contact Rosie.'
	]);
}
