<?php
	session_start();
	if(!isset($_SESSION['doctor']))
	{
		header("Location: index.html");
		exit();
	};

	$d1 = $_POST['date1'];
	$m1 = $_POST['month1'];
	$y1 = $_POST['year1'];
	$d2 = $_POST['date2'];
	$m2 = $_POST['month2'];
	$y2 = $_POST['year2'];

	if(checkdate($m1,$d1,$y1) && checkdate($m2,$d2,$y2))
	{
		include 'config.php';
		if(!($dbconn = @mysql_connect($dbhost, $dbuser, $dbpass))) exit('Error connecting to database.');
		mysql_select_db($db);

		$sqlq = "SELECT id,qty_buy_sell*sp AS SP FROM transaction AS a NATURAL JOIN txn_on NATURAL JOIN medicine WHERE txn_timestamp>='".$y1."-".sprintf("%02d",$m1)."-".sprintf("%02d",$d1)." 00:00:00' AND txn_timestamp<='".$y2."-".sprintf("%02d",$m2)."-".sprintf("%02d",$d2)." 23:59:59' AND buy_sell='S';";
		$sqlq = mysql_query($sqlq);
		if(!$sqlq)
		{
			echo "Query Failed.<br />";
			exit;
		}
		$num_rows = mysql_num_rows($sqlq);
		echo "<pre>Sale Transactions:<br /><br />";
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

		$sqlq = "SELECT id,qty_buy_sell*cp AS CP FROM transaction AS a NATURAL JOIN txn_on NATURAL JOIN medicine WHERE txn_timestamp>='".$y1."-".sprintf("%02d",$m1)."-".sprintf("%02d",$d1)." 00:00:00' AND txn_timestamp<='".$y2."-".sprintf("%02d",$m2)."-".sprintf("%02d",$d2)." 23:59:59' AND buy_sell='B';";
		$sqlq = mysql_query($sqlq);
		if(!$sqlq)
		{
			echo "Query Failed.<br />";
			exit;
		}
		$num_rows = mysql_num_rows($sqlq);
		echo "<pre>Purchase Transactions:<br /><br />";
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

		$sqlq = "SELECT SUM(qty_buy_sell*cp) AS CP FROM transaction AS a NATURAL JOIN txn_on NATURAL JOIN medicine WHERE txn_timestamp>='".$y1."-".sprintf("%02d",$m1)."-".sprintf("%02d",$d1)." 00:00:00' AND txn_timestamp<='".$y2."-".sprintf("%02d",$m2)."-".sprintf("%02d",$d2)." 23:59:59' AND buy_sell='B';";
		$sqlq = mysql_query($sqlq);
		if(!$sqlq)
		{
			echo "Query Failed.<br />";
			exit;
		}
		echo "<pre>Total expenses: Rs. ";
		$cp = mysql_fetch_array($sqlq);
		echo $cp['CP'];
		echo "<br />";

		$sqlq = "SELECT SUM(qty_buy_sell*sp) AS SP FROM transaction AS a NATURAL JOIN txn_on NATURAL JOIN medicine WHERE txn_timestamp>='".$y1."-".sprintf("%02d",$m1)."-".sprintf("%02d",$d1)." 00:00:00' AND txn_timestamp<='".$y2."-".sprintf("%02d",$m2)."-".sprintf("%02d",$d2)." 23:59:59' AND buy_sell='S';";
		$sqlq = mysql_query($sqlq);
		if(!$sqlq)
		{
			echo "Query Failed.<br />";
			exit;
		}
		echo "Total income: Rs. ";
		$sp = mysql_fetch_array($sqlq);
		echo $sp['SP'];
		echo "<br />";

		echo "Total profits: Rs. ";
		echo $sp['SP']-$cp['CP'];
		echo "</pre>";
	}
	else echo "Dates are Invalid";
?>
