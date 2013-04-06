<?php
	if( isset($_POST['submit']) )
	{
		include 'config.php';

		if(!($dbconn = @mysql_connect($dbhost, $dbuser, $dbpass))) exit('Error connecting to database.');
		mysql_select_db($db);
		
		$medno = $_POST['medno'];
		$m1det = $_POST['m1'];
		$m2det = $_POST['m2'];
		$m3det = $_POST['m3'];
		$m4det = $_POST['m4'];
		$m5det = $_POST['m5'];
		$noof1 = $_POST['noof1'];
		$noof2 = $_POST['noof2'];
		$noof3 = $_POST['noof3'];
		$noof4 = $_POST['noof4'];
		$noof5 = $_POST['noof5'];
		$exists = $_POST['cex'];
		$cname = $_POST['cname'];
		$caddr = $_POST['caddr'];
		$cem = $_POST['cem'];
		$ctel = $_POST['ctel'];
		$snotes = $_POST['snotes'];

		for($i=1;$i<=$medno;$i++)
		{
			$p = explode(",",${'m'.$i.'det'});
			${'m'.$i.'name'} = $p[0];
			${'m'.$i.'ts'}=$p[1];
			${'m'.$i.'expd'}=$p[2];
			${'m'.$i.'chemamt'}=$p[3];
			${'m'.$i.'cp'}=$p[4];
			${'m'.$i.'sp'}=$p[5];
		}

		echo "Customer Name -	".$cname."<br />";
		echo "Customer Addr -	".$caddr."<br />";
		echo "Customer Email -	".$cem."<br />";
		echo "Customer Tel -	".$ctel."<br /><br />";

		$bill = 0;

		for($i=1;$i<=$medno;$i++)
		{
			echo "<b><u>Medicine	".$i."</b></u><br />";
			echo "  Name -				".${'m'.$i.'name'}."<br />";
			echo "  Chemical Amount -		".${'m'.$i.'chemamt'}."<br />";
			echo "  Exp. Date (yyyy-mm-dd) -	".${'m'.$i.'expd'}."<br />";
			echo "  Selling Price(each) - Rs.	".${'m'.$i.'sp'}."<br />";
			echo "  Quantity -			".${'noof'.$i}."<br />";
			$cost = ${'m'.$i.'sp'}*${'noof'.$i};
			echo "  Selling Price - Rs.		".$cost."<br /><br />";
			$bill = $bill + $cost;
		}

		echo "***************************<br /><b>TOTAL BILL - Rs.	".$bill."</b><br />";

		if($exists == "N" || $exists == "Y")//merged cases because anyways the user can't be trusted to provide correct value
		{
			//add person, person_email, person_tel_no
			//create transaction, txn_on, txn_person
			//update medicine count in medicine

			//add person, person_email, person_tel_no
			$check = "SELECT * FROM person WHERE name='".$cname."' AND address='".$caddr."'";
			$query = mysql_query($check);
			$tempid = -1;
			$foundid= -1;
			if(mysql_num_rows($query)==0)
			{
				$insert = "INSERT INTO person (name,address) VALUES ('".$cname."','".$caddr."')";
				$query = mysql_query($insert);
				$tempid = mysql_insert_id();
				//insert into person_email
				if($cem !== '')
				{
					$insert = "INSERT INTO person_email (pid,email) VALUES ('".$tempid."','".$cem."')";
					$query = mysql_query($insert);
				}
				//insert into person_tel_no
				if($ctel !== '')
				{
					$insert = "INSERT INTO person_tel_no (pid,tel_no) VALUES ('".$tempid."','".$ctel."')";
					$query = mysql_query($insert);
				}
			}
			else
			{
				$query = mysql_fetch_array($query);
				$foundid = $query['pid'];
			}
			//echo "<br />Step 1 Done<br />";
			//create transaction, txn_on, txn_person
			$date = new DateTime();
			$ts = $date->getTimestamp();
			$insert = "INSERT INTO transaction (txn_timestamp,buy_sell,notes) VALUES ('".date('Y-m-d H:i:s',$ts)."','S','".$snotes."')";
			$query = mysql_query($insert);
			$txnid = mysql_insert_id();
			//insert into txn_on for each medicine
			for($i=1;$i<=$medno;$i++)
			{
				$insert = "INSERT INTO txn_on (name,buy_timestamp,expiry_date,chem_amount,cp,id,qty_buy_sell) VALUES ('".${'m'.$i.'name'}."','".${'m'.$i.'ts'}."','".${'m'.$i.'expd'}."','".${'m'.$i.'chemamt'}."','".${'m'.$i.'cp'}."','".$txnid."','".${'noof'.$i}."')";
				$query = mysql_query($insert);
			}
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
			//echo "<br />Step 2 Done<br />";

			//update medicine count in medicine
			for($i=1;$i<=$medno;$i++)
			{
				$check = "SELECT * FROM medicine WHERE name='".${'m'.$i.'name'}."' AND buy_timestamp='".${'m'.$i.'ts'}."' AND expiry_date='".${'m'.$i.'expd'}."' AND chem_amount='".${'m'.$i.'chemamt'}."' AND cp='".${'m'.$i.'cp'}."'";
				$query = mysql_query($check);
				$query = mysql_fetch_array($query);
				$qtythis = $query['qty'];
				$qtyf = $qtythis-${'noof'.$i};

				$update = "UPDATE medicine SET qty=".$qtyf.", buy_timestamp=buy_timestamp WHERE name='".${'m'.$i.'name'}."' AND buy_timestamp='".${'m'.$i.'ts'}."' AND expiry_date='".${'m'.$i.'expd'}."' AND chem_amount='".${'m'.$i.'chemamt'}."' AND cp=".${'m'.$i.'cp'}."";
				$query = mysql_query($update);
			}
			//echo "<br />Step 3 Done<br />";

			echo "<b>BILL NO......................................................".$txnid."</b><br />";
			echo "<b>TIMESTAMP...............................................".$ts."</b><br /><br />";

			echo "<br /><br />Debug:".mysql_error();
		}
	}
	else
	{
		header("Location: index.html");
	}
?>
