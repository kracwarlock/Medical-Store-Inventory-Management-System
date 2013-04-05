<?php
	include 'config.php';

	if(!($dbconn = @mysql_connect($dbhost, $dbuser, $dbpass))) exit('Error connecting to database.');
	mysql_select_db($db);
	
	$q=$_GET["q"];
	//$q = name,buy_timestamp,expiry_date,chem_amt,cp;
	$p = explode(",", $q);
	$name=$p[0];
	$ts=$p[1];
	$expd=$p[2];
	$chemamt=$p[3];
	$cp=$p[4];

	$check = "SELECT * FROM medicine WHERE name='".$name."' AND buy_timestamp='".$ts."' AND expiry_date='".$expd."' AND chem_amount='".$chemamt."' AND cp='".$cp."'";
	$query = mysql_query($check);
	$query = mysql_fetch_array($query);
	$qty = $query['qty'];
	echo $qty;
?>
