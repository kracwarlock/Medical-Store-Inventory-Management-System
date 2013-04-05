<?php
	session_start();
	if(!isset($_SESSION['receptionist']))
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

		<title>Medical Store Management</title>
	</head>

	<body>

<form id="medbuy" method="POST" action="buymeds.php">
	<center>
		<h1>Medical Store Management</h1>
		<hr />
	</center>

	<label for="purchase" style="font-size:25px;">Medicine Purchase:</label>
	<br /><br />

	<label for="name">Name:</label>
	<input type="text" name="name" required/>

	<label for="expirydate" class="desc">Expiry Date(YYYY-MM-DD):</label>
	<input type="text" name="expirydate" pattern="[0-9][0-9][0-9][0-9][-][0-9][0-9][-][0-9][0-9]" required/>
	<br />

	<label for="chemamt">Chemical Amount:</label>
	<input type="text" name="chemamt" required/>

	<label for="qty" class="desc">Quantity:</label>
	<input type="text" name="qty" pattern="[0-9]+" required/>
	<br />

	<label for="cp">Cost Price:</label>
	<input type="text" name="cp" pattern="[0-9]+" required/>

	<label for="sp" class="desc">Selling Price:</label>
	<input type="text" name="sp" pattern="[0-9]+" required/>
	<br />

	<label for="c1">Compound 1:</label>
	<input type="text" name="c1" required/>

	<label for="c2" class="desc">Compound 2:</label>
	<input type="text" name="c2" />
	<br />

	<label for="c3">Compound 3:</label>
	<input type="text" name="c3" />

	<label for="ph" class="desc">Pharma Co.:</label>
	<input type="text" name="ph" required/>
	<br />

	<label for="notes">Notes:</label>
	<input type="text" name="notes" />

	<label for="ex" class="desc">Existing Supplier:</label>
	<select name="ex">
		<option value="N">No</option>
		<option value="Y">Yes</option>
	</select>
	<br />

	<label for="sname" class="desc">Supplier Name:</label>
	<input type="text" name="sname" required/>

	<label for="saddr" class="desc">Supplier Address:</label>
	<input type="text" name="saddr" required/>
	<br />

	<label for="sem" class="desc">Supplier Email:</label>
	<input type="email" name="sem" required/>

	<label for="stel" class="desc">Supplier Tel. No.(only numbers):</label>
	<input type="text" name="stel" pattern="[0-9]+" required/>
	<br />

	<input type="submit" name="submit" value="Submit" class="submit" id="buysub"/>
</form>

<form id="sellmeds"  method="POST" action="sellmeds.php">

	<label for="question" style="font-size:25px;">Medicine Sale:</label>
	<br /><br />

	<label for="medno" class="desc">Select number of different medicines being sold:</label>
	<select name="medno">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
	</select>
	<br />

	<script	type="text/javascript">
		function getQty(str,idx)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					var select = document.getElementById("noof"+idx);
					select.options.length=0;
					var retqty = xmlhttp.responseText;
					var i=0;
					for(i=1;i<=retqty;i++)
					{
						var option = document.createElement("option");
						option.text = i;
						option.value = i;
						var select = document.getElementById("noof"+idx);
						select.appendChild(option);
					}
				}
			}
			xmlhttp.open("GET","getMedsDetails.php?q="+str,true);
			xmlhttp.send();
		}
	</script>

	<?php
		$getMeds = mysql_query('SELECT * FROM medicine');
	?>

	<label for="m1" class="desc">Select Medicine 1:</label>
	<select name="m1" onmouseup="getQty(this.options[this.selectedIndex].value,1);" onchange="getQty(this.options[this.selectedIndex].value,1);">
		<?php
			while ($row = mysql_fetch_array($getMeds))
			{
		?>
		<option value="<?php echo $row['name'].','.$row['buy_timestamp'].','.$row['expiry_date'].','.$row['chem_amount'].','.$row['cp'].','.$row['sp']; ?>"><?php echo $row['name'].','.$row['chem_amount'].','.$row['expiry_date'].','.$row['sp']; ?></option>
		<?php
			}
		?>
	</select>
	<select id="noof1" name="noof1"></select>
	<br />

	<?php
		$getMeds = mysql_query('SELECT * FROM medicine');
	?>

	<label for="m2" class="desc">Select Medicine 2:</label>
	<select name="m2" onmouseup="getQty(this.options[this.selectedIndex].value,2);" onchange="getQty(this.options[this.selectedIndex].value,2);">
		<?php
			while ($row = mysql_fetch_array($getMeds))
			{
		?>
		<option value="<?php echo $row['name'].','.$row['buy_timestamp'].','.$row['expiry_date'].','.$row['chem_amount'].','.$row['cp'].','.$row['sp']; ?>"><?php echo $row['name'].','.$row['chem_amount'].','.$row['expiry_date'].','.$row['sp']; ?></option>
		<?php
			}
		?>
	</select>
	<select id="noof2" name="noof2"></select>
	<br />

	<?php
		$getMeds = mysql_query('SELECT * FROM medicine');
	?>

	<label for="m3" class="desc">Select Medicine 3:</label>
	<select name="m3" onmouseup="getQty(this.options[this.selectedIndex].value,3);" onchange="getQty(this.options[this.selectedIndex].value,3);">
		<?php
			while ($row = mysql_fetch_array($getMeds))
			{
		?>
		<option value="<?php echo $row['name'].','.$row['buy_timestamp'].','.$row['expiry_date'].','.$row['chem_amount'].','.$row['cp'].','.$row['sp']; ?>"><?php echo $row['name'].','.$row['chem_amount'].','.$row['expiry_date'].','.$row['sp']; ?></option>
		<?php
			}
		?>
	</select>
	<select id="noof3" name="noof3"></select>
	<br />

	<?php
		$getMeds = mysql_query('SELECT * FROM medicine');
	?>

	<label for="m4" class="desc">Select Medicine 4:</label>
	<select name="m4" onmouseup="getQty(this.options[this.selectedIndex].value,4);" onchange="getQty(this.options[this.selectedIndex].value,4);">
		<?php
			while ($row = mysql_fetch_array($getMeds))
			{
		?>
		<option value="<?php echo $row['name'].','.$row['buy_timestamp'].','.$row['expiry_date'].','.$row['chem_amount'].','.$row['cp'].','.$row['sp']; ?>"><?php echo $row['name'].','.$row['chem_amount'].','.$row['expiry_date'].','.$row['sp']; ?></option>
		<?php
			}
		?>
	</select>
	<select id="noof4" name="noof4"></select>
	<br />

	<?php
		$getMeds = mysql_query('SELECT * FROM medicine');
	?>

	<label for="m5" class="desc">Select Medicine 5:</label>
	<select name="m5" onmouseup="getQty(this.options[this.selectedIndex].value,5);" onchange="getQty(this.options[this.selectedIndex].value,5);">
		<?php
			while ($row = mysql_fetch_array($getMeds))
			{
		?>
		<option value="<?php echo $row['name'].','.$row['buy_timestamp'].','.$row['expiry_date'].','.$row['chem_amount'].','.$row['cp'].','.$row['sp']; ?>"><?php echo $row['name'].','.$row['chem_amount'].','.$row['expiry_date'].','.$row['sp']; ?></option>
		<?php
			}
		?>
	</select>
	<select id="noof5" name="noof5"></select>
	<br />

	<label for="cex" class="desc">Existing Customer:</label>
	<select name="cex">
		<option value="N">No</option>
		<option value="Y">Yes</option>
	</select>
	<br />

	<label for="cname" class="desc">Customer Name:</label>
	<input type="text" name="cname" required/>

	<label for="caddr" class="desc">Customer Address:</label>
	<input type="text" name="caddr" required/>
	<br />

	<label for="cem" class="desc">Customer Email:</label>
	<input type="email" name="cem" required/>

	<label for="ctel" class="desc">Customer Tel. No.(only numbers):</label>
	<input type="text" name="ctel" pattern="[0-9]+" required/>
	<br />

	<label for="snotes">Notes:</label>
	<input type="text" name="snotes" />
	<br />

	<!-- check that none of the options are same -->
	<input type="submit" name="submit" value="Submit" class="submit" id="sellsub"/>
</form>

	</body>
</html>
