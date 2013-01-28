<?php
	session_start();
	if(!isset($_SESSION['doctor']))
	{
		header("Location: index.html");
	}
?>