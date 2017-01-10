<?php

	require 'db_connect.php';
	session_start();
	
	class db_functions
	{
		private $name, $email, $username, $password;

		public function __construct()
		{
			$db = new db_connect;
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

		public function Login($email, $password)
		{
			$sql = mysql_query("Select $email, password from users");
			{
				if(mysql_num_rows($sql)==1)
				{
					$user_data = mysql_fetch_array($sql);
					$_SESSION['name'] = $user_data['name'];
					$_SESSION['username'] = $user_data['username'];
					$_SESSION['email'] = $user_data['email'];
				}
				else
				{
					echo "Wrong email or password entered";
				}
			}
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

	function validateDataLogin()
	{
		$email = $_POST['email'];
		$password = $email = $_POST['password'];

		if(empty($email))
			throw new Exception("Enter your emailId to Login");

		if(empty($password))
			throw new Exception("Please enter your password to Login");

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

	if(isset($_POST['register']))
	{
		validateDataRegistration();
	}

	if(isset($_POST['login']))
	{
		validateDataLogin();
	}
?>