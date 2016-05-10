<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post("/api/auth/login", function (Request $request, Response $response) {
	// get JSON data
	$data = $request->getParsedBody();

	// create response object
	$responseObject = [];

	// remember login
	$remember = isset($data['remember']) && $data['remember'] == 'on' ? true : false;

	// authenticate
	try {
		if (!$this->sentinel->authenticate([
			'email' => $data['email'],
			'password' => $data['password'],
		], $remember)) {
			$responseObject['status'] = 401;
			$responseObject['data'] = [];
        	$responseObject['errors'][] = "Wrong username or password";
        	$responseObject['message'] = "Login Unsuccessful";
		} else {
			$responseObject['status'] = 200;
			$responseObject['data'] = ['redirect' => '/dashboard'];
        	$responseObject['message'] = "Login Successful";
		}
	} catch (Cartalyst\Sentinel\Checkpoints\ThrottlingException $ex) {
		$responseObject['status'] = 401;
		$responseObject['data'] = [];
    	$responseObject['errors'][] = "Too many login attempts";
    	$responseObject['message'] = "Login Unsuccessful";
	} catch (Cartalyst\Sentinel\Checkpoints\NotActivatedException $ex){
		$responseObject['status'] = 401;
		$responseObject['data'] = [];
    	$responseObject['errors'][] = "Account not activated.";
    	$responseObject['message'] = "Login Unsuccessful";
	}
	
	// set response status and body
	$newResponse = $response->withStatus($responseObject['status']);
	$newResponse->getBody()->write(json_encode($responseObject));

	return $newResponse;
});

$app->get("/api/auth/logout", function (Request $request, Response $response) {
	$this->sentinel->logout(null,true);
	
	$responseObject['status'] = 200;
	$responseObject['data'] = ['redirect' => '/dashboard'];
	$responseObject['message'] = "Logout Successful";
});