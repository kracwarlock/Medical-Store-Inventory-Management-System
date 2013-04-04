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

		echo "UPDATING RECORDS.............<br /><br />";

		echo "Medicine Name -	".$medname."<br />";
		echo "Expiry Date -	".$expdate."<br />";
		echo "Chemical Amount -	".$chemamt."<br />";
		echo "Quantity -	".$qty."<br />";
		echo "Cost Price -	".$cp."<br />";
		echo "Selling Price -	".$sp."<br />";
		echo "Major Compound -	".$c1."<br />";
		echo "Minor Compound1 -	".$c2."<br />";
		echo "Minor Compound2 -	".$c3."<br />";
		echo "Pharma Co. -	".$ph."<br />";
		echo "Notes -		".$notes."<br />";
		echo "Existing Supp -	".$exists."<br />";
		//echo "Supp ID -		"."<br />";//calculate later
		echo "Supp Name -	".$sname."<br />";
		echo "Supp Addr -	".$saddr."<br />";
		echo "Supp Email -	".$sem."<br />";
		echo "Supp Tel -	".$stel."<br /><br />";

		echo "***************RECORDS UPDATED SUCCESSFULLY**************<br /><br />";
		echo "Returning back in 5 seconds..............";
		header("refresh:5;url=med_store_reception.php");

		if($exists == "N" || $exists == "Y")//merged cases because anyways the user can't be trusted to provide correct value
		{
			$date = new DateTime();
			$ts = $date->getTimestamp();
			//insert into medicine
			$insert = "INSERT INTO medicine (name,buy_timestamp,expiry_date,chem_amount,qty,cp,sp) VALUES ('".$medname."','".date('Y-m-d H:i:s',$ts)."','".$expdate."','".$chemamt."','".$qty."','".$cp."','".$sp."')";
			$query = mysql_query($insert);
			//insert into name_pharma
			$insert = "INSERT INTO name_pharma (name,pharmaco) VALUES ('".$medname."','".$ph."')";
			$query = mysql_query($insert);
			//insert into name_compound
			$insert = "INSERT INTO name_compound (name,compound) VALUES ('".$medname."','".$c1."')";
			$query = mysql_query($insert);
			if($c2 !== '')
			{
				$insert = "INSERT INTO name_compound (name,compound) VALUES ('".$medname."','".$c2."')";
				$query = mysql_query($insert);
			}
			if($c3 !== '')
			{
				$insert = "INSERT INTO name_compound (name,compound) VALUES ('".$medname."','".$c3."')";
				$query = mysql_query($insert);
			}
			//insert into person if not exists
			$check = "SELECT * FROM person WHERE name='".$sname."' AND address='".$saddr."'";
			$query = mysql_query($check);
			$tempid = -1;
			$foundid= -1;
			if(mysql_num_rows($query)==0)
			{
				$insert = "INSERT INTO person (name,address) VALUES ('".$sname."','".$saddr."')";
				$query = mysql_query($insert);
				$tempid = mysql_insert_id();
				//insert into person_email
				if($sem !== '')
				{
					$insert = "INSERT INTO person_email (pid,email) VALUES ('".$tempid."','".$sem."')";
					$query = mysql_query($insert);
				}
				//insert into person_tel_no
				if($stel !== '')
				{
					$insert = "INSERT INTO person_tel_no (pid,tel_no) VALUES ('".$tempid."','".$stel."')";
					$query = mysql_query($insert);
				}
				//insert into supplier_pharmaco
				$insert = "INSERT INTO supplier_pharmaco (pid,pharmaco) VALUES ('".$tempid."','".$ph."')";
				$query = mysql_query($insert);
			}
			else
			{
				$query = mysql_fetch_array($query);
				$foundid = $query['pid'];
				//insert into supplier_pharmaco
				$insert = "INSERT INTO supplier_pharmaco (pid,pharmaco) VALUES ('".$foundid."','".$ph."')";
				$query = mysql_query($insert);
			}
			//insert into transaction
			$insert = "INSERT INTO transaction (txn_timestamp,buy_sell,notes) VALUES ('".date('Y-m-d H:i:s',$ts)."','B','".$notes."')";
			$query = mysql_query($insert);
			$txnid = mysql_insert_id();
			//insert into txn_on
			$insert = "INSERT INTO txn_on (name,buy_timestamp,expiry_date,chem_amount,cp,id,qty_buy_sell) VALUES ('".$medname."','".date('Y-m-d H:i:s',$ts)."','".$expdate."','".$chemamt."','".$cp."','".$txnid."','".$qty."')";
			$query = mysql_query($insert);
			//insert into txn_person
			$pidp = -1;
			if($foundid != -1) $pidp = $foundid;
			else $pidp = $tempid;
			$check = "SELECT * FROM person WHERE name='receptionist'";
			$query = mysql_query($check);
			$query = mysql_fetch_array($query);
			$pidrecp = $query['pid'];
			$insert = "INSERT INTO txn_person (id,pid_person,pid_employee) VALUES ('".$txnid."','".$pidp."','".$pidrecp."')";
			$query = mysql_query($insert);

			echo "<br /><br />Debug:".mysql_error();
		}
	}
	else
	{
		header("Location: index.html");
	}
?>
