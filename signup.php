<?php

	require_once('db_connect.php');

	class signup
	{
		private $name;
		private $username;
		private $email;
		private $password;
		private $confirm_password;
		private $regex_name = '/^[a-zA-Z][a-zA-Z ]*$/';
		private $username_available = array();
		private $name_as_username;
		private $first_name_as_username;
		private $last_name_as_username;

		public function __set($property, $value)
		{
			if(property_exists($this, $property))
			{
				$this->$property = $value;
			}
		}

		public function __get($property)
		{
			if(property_exists($this, $property))
			{
				return $this->$property;
			}
		}

		function __construct($name, $username, $email, $password, $confirm_password)
		{
			try
			{
				if(empty($name))
					throw new Exception("Name can't be blank");

				if(empty($username))
					throw new Exception("Please enter an username");

				if(empty($email))
					throw new Exception("Email Address can't be blank");

				if(empty($password))
					throw new Exception("Password can't be blank");

				if(empty($confirm_password))
					throw new Exception("Confirm Passsword field can't be blank");

				if(!preg_match($this->regex_name, $name))
					throw new Exception("Name can contain letters and whitespaces only");

				if(!filter_var($email, FILTER_VALIDATE_EMAIL))
					throw new Exception("Enter a valid email address");

				if(strlen($password) < 6)
					throw new Exception("Password must contain alteast 6 characters");

				if($password!=$confirm_password)
					throw new Exception("Password doesn't match");

				$db_connect = new db_connect;

				$this->isUserExists($email);	// checking if user already exists

				if($this->isUsernameTaken($username))
				{
					$this->suggestUserName($name, $email);
				}

				$this->name = $name;
				$this->username = $username;
				$this->email = $email;
				$this->password = md5($password);

				$this->register();

			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}

		private function isUserExists($email)
		{
			$sql = mysql_query("Select * from users where email = $email");
			try
			{
				if(!$sql)
					throw new Exception("Unable to execute query for isUserExists function");
					
				$result = mysql_fetch_array($sql);
				if(mysql_num_rows($result)==1)
				{
					throw new Exception("Email Address already exists");
				}
				
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}

		private function isUsernameTaken($username)
		{
			$sql = mysql_query("Select * from users where username = $username");
			try
			{
				if(!$sql)
					throw new Exception("unable to execute query for isUsernameTaken function");
					
				$result = mysql_fetch_array($sql);
				if(mysql_num_rows($result)==1)
					return true;
				else
					return false;
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}

		private function suggestUserName($name, $email)
		{
			$this->name_as_username = explode(" ", $name);
			$this->first_name_as_username = $name_as_username[0];
			$this->last_name_as_username = $name_as_username[1];
			for($i=0; $i<3; $i++)
			{
				$this->username_available[$i] = (mt_rand()%2==0) ? ($this->first_name_as_username + "_" + mt_rand(11,999)) : ($this->last_name_as_username + "_" + mt_rand(11,999));
			}

			echo "Usernames available: $this->username_available[0], $this->username_available[1], $this->username_available[2]";
		}

		private function register()
		{
			$sql = mysql_query("INSERT INTO users('name', 'username', 'email', 'password') VALUES('".$this->name."', '".$this->username."', '".$this->email."', '".$this->password."' )");
			try
			{
				if(!$sql)				
					throw new Exception("Unable to insert record in the table");
				else
					echo "Successfully registered";
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		
		}
	}

	// if(isset($_POST['submit']))
	// {
		$name = "kartik";
		$username = "kartik";
		$email = "chauhan.kartik25@gmail.com";
		$password = "heyhey";
		$confirm_password = "heyhey";
		// $name = mysql_real_escape_string($_POST['name']);
		// $username = mysql_real_escape_string($_POST['username']);
		// $email = mysql_real_escape_string($_POST['email']);
		// $password = mysql_real_escape_string($_POST['password']);
		// $confirm_password = mysql_real_escape_string($_POST['confirm_password']);

		$newUser = new signup($name, $username, $email, $password, $confirm_password);

	// }
	// else
	// {
	// 	echo "not sent through form";
	// }

?>