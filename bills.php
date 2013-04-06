<?php
	session_start();
	if(!isset($_SESSION['doctor']))
	{
		header("Location: index.html");
		exit();
	};

	$txnid = $_POST['txn_id'];

	include 'config.php';
	if(!($dbconn = @mysql_connect($dbhost, $dbuser, $dbpass))) exit('Error connecting to database.');
	mysql_select_db($db);


	$sqlq = "SELECT * FROM (SELECT * FROM transaction WHERE id='".$txnid."') AS a NATURAL JOIN txn_on NATURAL JOIN medicine;";
	$sqlq = mysql_query($sqlq);
	if(!$sqlq)
	{
		echo "Query Failed.<br />";
		exit;
	}
	$num_rows = mysql_num_rows($sqlq);
	echo "<pre>Medicine Info:<br /><br />";
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
	echo "</table></pre>";

	$sqlq = "SELECT * FROM (SELECT pid_person FROM txn_person WHERE id='".$txnid."') AS a JOIN person ON (a.pid_person=person.pid)";
	$sqlq = mysql_query($sqlq);
	if(!$sqlq)
	{
		echo "Query Failed.<br />";
		exit;
	}
	$num_rows = mysql_num_rows($sqlq);
	echo "<pre>Buyer/Seller Info:<br /><br />";
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
	echo "</table></pre>";

	$txntype=-1;
	$sqlq = "SELECT * FROM transaction WHERE id='".$txnid."'";
	$sqlq = mysql_query($sqlq);
	$sqlq = mysql_fetch_array($sqlq);
	if($sqlq['buy_sell']=='B') $txntype=1;
	else if($sqlq['buy_sell']=='S') $txntype=0;

	if($txntype==0)	//	S
	{
		$sqlq = "SELECT SUM(qty_buy_sell*sp) AS 'Received from Customer' FROM (SELECT * FROM transaction WHERE id='".$txnid."') AS a NATURAL JOIN txn_on NATURAL JOIN medicine;";
		$sqlq = mysql_query($sqlq);
		if(!$sqlq)
		{
			echo "Query Failed.<br />";
			exit;
		}
		$num_rows = mysql_num_rows($sqlq);
		echo "<pre>Bill:<br /><br />";
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
		echo "</table></pre>";
	}
	else if($txntype==1) //	B
	{
		$sqlq = "SELECT SUM(qty_buy_sell*cp) AS 'Paid to Supplier' FROM (SELECT * FROM transaction WHERE id='".$txnid."') AS a NATURAL JOIN txn_on NATURAL JOIN medicine;";
		$sqlq = mysql_query($sqlq);
		if(!$sqlq)
		{
			echo "Query Failed.<br />";
			exit;
		}
		$num_rows = mysql_num_rows($sqlq);
		echo "<pre>Bill:<br /><br />";
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
		echo "</table></pre>";
	}
?>
