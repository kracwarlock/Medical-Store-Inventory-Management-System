<?php
	session_start();
	if(!isset($_SESSION['med_admin']))
	{
		header("Location: index.html");
		exit();
	}
	if(isset($_POST['sqlq']))
	{
		$sqlq = $_POST['sqlq'];

		include 'config.php';
		if(!($dbconn = @mysql_connect($dbhost, $dbuser, $dbpass))) exit('Error connecting to database.');
		mysql_select_db($db);
		$sqlq = mysql_query($sqlq);
		if(!$sqlq)
		{
			echo "Query Failed.<br />";
			exit;
		}
		$num_rows = mysql_num_rows($sqlq);
		echo "<pre>Fetched ".$num_rows." rows. Output:<br /><br />";
		echo "<table border=1><tr>";
		for($i = 0; $i < mysql_num_fields($sqlq); $i++)
		{
    			$field_info = mysql_fetch_field($sqlq, $i);
			echo "<th>{$field_info->name}</th>";
		}
		echo "</tr>";
		while($row = mysql_fetch_row($sqlq))
		{
			echo "<tr>";
			foreach($row as $_column)
			{
				echo "<td>{$_column}</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
?>
