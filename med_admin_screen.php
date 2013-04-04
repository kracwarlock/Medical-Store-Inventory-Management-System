<?php
	session_start();
	if(!isset($_SESSION['med_admin']))
	{
		header("Location: index.html");
		exit();
	}
	include 'config.php';
	if(!($dbconn = @mysql_connect($dbhost, $dbuser, $dbpass))) exit('Error connecting to database.');
	mysql_select_db($db);
?>
<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Graduate' rel='stylesheet' type='text/css'>
		<![if !IE]>
		<link href='css/style.css' rel='stylesheet' type='text/css'>
		<![endif]>
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script>
			function ajaxForQuery()
			{
				$.ajax({type:'POST', url: 'runsqlq.php', data:$('#medadmin').serialize(), success: function(response)
				{
					$('#medadmin').find('.outputsqlq').html(response);
				}
				});
				return false;
			}
		</script>

		<title>Medical Store Management</title>
	</head>

	<body>

		<form id="medadmin" style="height:680px;" onsubmit="return ajaxForQuery();">
			<center>
				<h1>Medical Store Management - ADMIN</h1>
				<hr />
			</center>

			<label for="sqlq" style="font-size:25px;">Run SQL Query (MySQL 5.1 Syntax):</label>
			<br /><br />

			<label for="sqlq">Query:</label>
			<input type="text" name="sqlq" style="width:820px;" required/>
			<br />

			<input type="submit" name="submit" value="Submit" class="submit" id="runsqlq"/>

			<div class="outputsqlq" style="border:2px solid black;"></div>
		</form>

	</body>
</html>
