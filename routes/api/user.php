<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post("/api/user", function (Request $request, Response $response) use($mail) {
	// get JSON data
	$data = $request->getParsedBody();
	
	$role = (isset($data['role'])) ? $data['role'] : 'Customer';
	
	// create response object
	$responseObject = [];
	
	// get role object
    $role = $this->sentinel->findRoleByName($role);

	// if a user exists with the same email
    if ($this->sentinel->findByCredentials([
        'login' => $data['email'],
    ])) {
		// send error response
		$responseObject['status'] = 400;
        $responseObject['message'] = 'User already exists with this email.';
    } else {
		// save new user
	    $user = $this->sentinel->create([
	        'first_name' => $data['firstname'],
	        'last_name' => $data['lastname'],
	        'email' => $data['email'],
	        'password' => $data['password']
	    ]);

	    // attach the user to the role
	    $role->users()->attach($user);

	    // create a new activation for the registered user
	    $activation = (new Cartalyst\Sentinel\Activations\IlluminateActivationRepository)->create($user);

		// send the activation email
		$mail->addAddress($data['email'], $data['firstname'] . ' ' . $data['lastname']);
		$mail->Subject = 'Activate Your Account';
		$mail->Body = "Welcome.<br /><p>Please <a href=\" " . PROTOCOL . "://" . HOST . "/activate/{$activation->code}/{$user->id}\">Activate your account</a> now.";
		if ($mail->send()) {
			// send confirmation message
			$responseObject['status'] = 201;
			$responseObject['data'] = $user;
        	$responseObject['message'] = "Please check your email to complete your account registration.";
		} else {
			$responseObject['status'] = 500;
        	$responseObject['message'] = $mail->ErrorInfo;
		}
	}
	
	// set response status and body
	$newResponse = $response->withStatus($responseObject['status']);
	$newResponse->getBody()->write(json_encode($responseObject));

	return $newResponse;	
});
?>