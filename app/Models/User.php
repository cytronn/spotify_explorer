<?php namespace app\Models; 

class User
{
	public function __construct($app)
	{
		$this->app = $app;
	}

	public function insertOne($name,$email,$password)
	{
		$password = md5($password);

		$prepare = $this->app['db']->prepare('
			INSERT INTO 
				users (name,password,mail)
			VALUES 
				(:name,:password,:mail)
		');

		$prepare->bindValue('name', $name);
		$prepare->bindValue('mail', $email);
		$prepare->bindValue('password', $password);
		if ($prepare->execute())
		{
			$this->setSession($name, $email);
		}		
	}

	public function logIn($name,$password)
	{
		$password = md5($password);

		$prepare = $this->app['db']->prepare('
			SELECT
				*
			FROM
				users
			WHERE
				name = :name
			AND 
				password = :password 
		');

		$prepare->bindValue('name', $name);
		$prepare->bindValue('password', $password);
		$prepare->execute();

		if ($prepare->rowCount() > 0)
		{
			$this->setSession($name);
			return 'redirect';
		} else
		{
			return 'error';
		}
	}

	public function setSession($name)
	{

		$this->app['session']->set('name', $name);
		$prepare = $this->app['db']->query("SELECT * FROM users WHERE name = '$name'");

		$id = $prepare->fetch();

		$this->app['session']->set('email', $id->mail);
		$this->app['session']->set('id', $id->ID);

	}

	public function getOneCollection($user_id)
	{
		$user_id = (int)$user_id;
		$prepare = $this->app['db']->prepare ('
			SELECT 
				a.*
			FROM 
				albums_collections AS ac
			LEFT JOIN
				albums AS a
			ON
				a.ID = ac.ID_album
			WHERE 
				ID_user = :ID_user
		');

		$prepare->bindValue('ID_user', $user_id);
		$prepare->execute();

		$collection = $prepare->fetchAll();
		return $collection;
	}

	public function checkAvailability($name, $email)
	{
		$error = array();
		$prepare_name = $this->app['db']->prepare('
			SELECT 
				*
			FROM 
				users 
			WHERE 
				name = :name
		');

		$prepare_name->bindValue('name', $name);
		$prepare_name->execute();

		

		if ($prepare_name->rowCount() > 0) 
		{
				$error['name'] = 0;
		} else 
		{
				$error['name'] = 1;
		}

		$prepare_email = $this->app['db']->prepare('
			SELECT 
				*
			FROM 
				users 
			WHERE 
				mail = :email
		');

		$prepare_email->bindValue('email', $email);
		$prepare_email->execute();

		if ($prepare_email->rowCount() > 0) 
		{
				$error['mail'] = 0;

		} else 
		{
				$error['mail'] = 1;
		}

		return $error;
	}
}