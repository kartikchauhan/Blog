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

		public function Register($name, $email, $username, $password)
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

?>