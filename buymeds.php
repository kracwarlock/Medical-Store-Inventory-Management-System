<?php
	if( isset($_POST['submit']) )
	{
		include 'config.php';

		if(!($dbconn = @mysql_connect($dbhost, $dbuser, $dbpass))) exit('Error connecting to database.');
		mysql_select_db($db);
		
		$medname = $_POST['name'];
		$expdate = $_POST['expirydate'];
		$chemamt = $_POST['chemamt'];
		$qty = $_POST['qty'];
		$cp = $_POST['cp'];
		$sp = $_POST['sp'];
		$c1 = $_POST['c1'];
		$c2 = $_POST['c2'];
		$c3 = $_POST['c3'];
		$ph = $_POST['ph'];
		$notes = $_POST['notes'];
		$exists = $_POST['ex'];
		$sname = $_POST['sname'];
		$saddr = $_POST['saddr'];
		$sem = $_POST['sem'];
		$stel = $_POST['stel'];

		echo "UPDATING RECORDS.............\n\n";

		echo "Medicine Name -	".$medname;
		echo "Expiry Date -	".$expdate;
		echo "Chemical Amount -	".$chemamt;
		echo "Quantity -	".$qty;
		echo "Cost Price -	".$cp;
		echo "Selling Price -	".$sp;
		echo "Major Compound -	".$c1;
		echo "Minor Compound1 -	".$c2;
		echo "Minor Compound2 -	".$c3;
		echo "Pharma Co. -	".$ph;
		echo "Notes -		".$notes;
		echo "Existing Supp -	".$ex;
		echo "Supp ID -		";
		echo "Supp Name -	".$sname;
		echo "Supp Addr -	".$saddr;
		echo "Supp Email -	".$sem;
		echo "Supp Tel -	".$stel;

		echo "***************RECORDS UPDATED SUCCESSFULLY************";
		header("Location: med_store_reception.php");

/*		$getCreds = mysql_query("SELECT role FROM ".$dbtable." WHERE username='".$username."' AND password='".$password."'");
		$gotCreds = mysql_fetch_array($getCreds);
*/
	}
	else
	{
		header("Location: index.html");
	}
?>