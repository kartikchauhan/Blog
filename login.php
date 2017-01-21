<?php

	require 'db_connect.php';	// establishing connection with the database
	session_start();
	
	class login
	{
		private $email, $password;

		function __construct($email, $password)
		{
			try
			{
				if(empty($email))
					throw new Exception("Enter your emailId to Login");

				if(empty($password))
					throw new Exception("Please enter your password to Login");

				if(!filter_var($email, FILTER_VALIDATE_EMAIL))
					throw new Exception("Please enter a valid email address");

				if(strlen($password) < 6)
					throw new Exception("Password must contain atleast 6 characters");

				$this->email = $email;
				$this->password = $password;

				matchUserCredentials();		// calling matchUserCredentials to match the email and password entered in the database
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}

		private function matchUserCredentials($email, $password)
		{
			$sql = mysql_query("Select * from users where email = $email and password = $password");	// selecting the record which match with the values entered by the user
			{
				try
				{
					if(mysql_num_rows($sql)==1)		// if any record found then proceed
					{
						$user_data = mysql_fetch_array($sql);
						$_SESSION['username'] = $user_data['username'];
					}
					else
					{
						throw new Exception("Wrong email or password");
					}
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
				}
			}
		}

	}
			

			try
			{
				$loginUser = new db_functions;
				$loginUser->Login($email, $password);
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}

		public function Register($name, $username, $email, $password)
		{
			if(isUserExists($email))
			{
				return false;
			}
			$password = md5($password);
			$sql = mysql_query("INSERT INTO users('name', 'email', 'username', 'password') values('".$name."', '".$email."', '".$username."', '".$password."')") or die("unable to insert record in the table");
			return $sql;
		}

		

		public function isUserExists($email)
		{
			$sql = mysql_query("Select email from users");
			return mysql_num_rows($sql);
		}
	}

	function validateDataRegistration()
	{
		
		$name = $_POST['name'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $email = $_POST['password'];
		$confirm_password = $email = $_POST['confirm_password'];
		if(empty($name))
			throw new Exception("Enter your name first before submitting");

		if(empty($username))
			throw new Exception("Enter a username first before submitting");

		if(empty($email))
			throw new Exception("Enter your emailId first before submitting");
		$username = $_POST['username'];

		if(empty($password))
			throw new Exception("Enter a password first before submitting");

		if(empty($confirm_password))
			throw new Exception("Please enter your password again");

		if($confirm_password!=$password)
			throw new Exception("Password doesn't match. Please try again");

		try
		{
			$registerUser = new db_functions;
			$registerUser->Register($name, $username, $email, $password);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}

	

	if(isset($_POST['register']))
	{
		validateDataRegistration();
	}

	if(isset($_POST['login']))
	{
		validateDataLogin();
	}
?>