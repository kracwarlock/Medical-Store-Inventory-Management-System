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

<form id="a1" onsubmit="return submitForm();">

	<label for="question" style="font-size:25px;">Medicine Sale:</label>
	<br /><br />

	<label for="p1">Predicate:</label>
	<input type="text" name="p1" />
	<label for="d1" class="desc">Description:</label>
	<input type="text" name="d1" />
	<br />

	<input type="submit" name="submit" value="Submit" class="submit" id="hmm"/>
</form>

	</body>
</html>
