<?php

namespace App\Models;

use Illuminate\Database\QueryException;

class QuizUsers
{
	private $db;

	public function __construct()
	{
		$this->db = app('db');
	}

	public function getUser($username, $password)
	{
		$sql = "
			SELECT	*
			FROM	users
			WHERE	emailaddress = ?
			AND	password = ?
		";

		$users = $this->db->select($sql, [$username, $password]);

		die ( print_r($users) );
	}


	public function createUser( $params = array() )
	{
		$sql = "
			CALL register_user(
				:usertypecode,
				:emailaddress,
				:password,
				:forename,
				:surname,
				:registerconfirmcode
			);
		";

		try {
			$newuser = $this->db->select($sql, $params);
		}
		catch ( QueryException $e )
		{
			return false;
		}

		return $newuser[0];
	}
}
