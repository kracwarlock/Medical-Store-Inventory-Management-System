<?php
	session_start();
	if(!isset($_SESSION['doctor']))
	{
		header("Location: index.html");
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
			function ajaxForQuery(id)
			{
				$.ajax({type:'POST', url: 'ownersqlq.php', data:{name:id}, success: function(response)
				{
					$('#medowner').find('#'+id).html(response);
				}
				});
				return false;
			}
			ajaxForQuery("medicines");
			ajaxForQuery("medicines_compounds");
			ajaxForQuery("medicines_pharma");
			ajaxForQuery("employees");
			ajaxForQuery("transactions");

			function bills()
			{
				$.ajax({type:'POST', url: 'bills.php', data:$('#medownerbills').serialize(), success: function(response)
				{
					$('#medownerbills').find('#billsdisp').html(response);
				}
				});
				return false;
			}
		</script>
		<title>Medical Store Management</title>
	</head>

	<body>

		<form id="medowner" style="height:;">
			<center>
				<h1>Medical Store Management - OWNER</h1>
				<hr />
			</center>

			<label for="tables" style="font-size:25px;">Current Status of Tables:</label>
			<br /><br />

			<div id="medicines" 		class="outputsqlq" style="border:2px solid black;"></div>
			<div id="medicines_compounds" 	class="outputsqlq" style="border:2px solid black;"></div>
			<div id="medicines_pharma" 	class="outputsqlq" style="border:2px solid black;"></div>
			<div id="employees" 		class="outputsqlq" style="border:2px solid black;"></div>
			<div id="transactions" 		class="outputsqlq" style="border:2px solid black;"></div>
		</form>

		<form id="medownerbills" style="height:;"  onsubmit="return bills();">

			<?php
				$getTxnId = mysql_query('SELECT id FROM transaction;');
			?>

			<label for="bills" style="font-size:25px;">Show Bill For Transaction ID:</label>
			<select name="txn_id">
				<?php
					while ($row = mysql_fetch_array($getTxnId))
					{
				?>
				<option value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></option>
				<?php
					}
				?>
			</select>
			<input type="submit" name="submit" value="Submit" class="submit" id="txnsub"/>
			<br /><br />

			<div id="billsdisp" class="outputsqlq" style="border:2px solid black;"></div>
		</form>
	</body>
</html>
