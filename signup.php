<?php

	class signup
	{
		function __construct($name, username, $email, $password, $confirm_password)
		{
			private $regex_name = '/^[a-zA-Z][a-zA-Z ]*$/';
			private $username_available = new splFixedArray(3);
			private $name_as_username;
			private $first_name_as_username;
			private $last_name_as_username;

			private function __set($property, $value)
			{
				if(property_exists($this, $property))
				{
					$this->$property = $value;
				}
			}

			private function __get($property)
			{
				if(property_exists($this, $property))
				{
					return $this->$property;
				}
			}

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

				if(!preg_match($regex_name, $name))
					throw new Exception("Name can contain letters and whitespaces only");

				if(filter_var($email, FILTER_VALIDATE_EMAIL))
					throw new Exception("Enter a valid email address");

				if(strlen($password) < 6)
					throw new Exception("Password must contain alteast 6 characters");

				if($password!=$confirm_password)
					throw new Exception("Password doesn't match");

				isUserExists($email);	// checking if user already exists

				if(isUsernameTaken($username))
				{
					suggestUserName($name, $email);
				}

			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}

			private function isUserExists($)
			{
				$sql = mysql_query("Select * from users where email = $email");
				try
				{
					if(mysql_num_rows($sql)==1)
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
				if(mysql_num_rows($sql)==1)
					return true;
				else
					return false;
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

			}
		}

	}
	


	if(isset($_POST['submit']))
	{
		$newUser = new signup($_POST['name'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);
	}
	else
	{
		header("home.html");
	}

?>