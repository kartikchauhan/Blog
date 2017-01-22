<?php
	
	class db_connect
	{
		function __construct()
		{
			require_once 'config.php';
			$conn = mysql_connect(host, username, password);
			mysql_select_db(database, $conn);
			if(!$conn)
			{
				echo "unable to connect to the database";
			}
			else
			{
				echo "connected to the database<br>";
			}
			return $conn;
		}

		public function Close()
		{
			mysql_close();
			echo "connection closed";
		}

	}

?>