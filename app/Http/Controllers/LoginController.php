<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends QuizUserController
{
	private $quizusers;

	public function __construct()
	{
		$this->quizusers = new \App\Models\QuizUsers();
	}

	public function login()
	{
		$user = $this->quizusers->getUser($_POST['username'], $_POST['password']);
		die ( print_r($user) );
	}

	public function register(Request $request)
	{
		$params = json_decode($request->getContent(), 1);
		$params['registerconfirmcode'] = uniqid();
		$params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

		$request->merge($params);

		$errors = $this->validate($request, array
			(
				'emailaddress'	=> 'required|email|max:200',
				'password'	=> 'required|max:250',
				'forename'	=> 'required|max:200',
				'surname'	=> 'required|max:200'
			)
		);

		/*
		if ( $errors )
		{
			$response = array
			(
				'errors' => $errors
			);
			die ( print_r($response) );
			return response()->json($response)->header('Status', 400);
		}
		*/

		$success = $this->quizusers->createUser($params);

		if ( $success->success )
		{
			// Send Email
			// $success->registerconfirmcode
			return new Response('', 200);
		}
		else
		{
			// Error
			$response = array
			(
				'errors' => array($success->error_message)
			);
			return response()->json($response)->setStatusCode(400, 'Errors');
		}
	}
}
