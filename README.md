Medical Store Inventory Management System
=========================================

This is my course project for CS315 - Introduction To Database Systems course at IIT Kanpur.

Instructions To Setup The Project
---------------------------------
1.	Rename sample-config.php to config.php and change the variable values inside it.

2.	Have a MySQL server ready and run the following queries as root:
	
	    CREATE USER '$dbuser'@'$dbhost' IDENTIFIED BY '$dbpass';
	    CREATE DATABASE $db;
	    GRANT ALL ON $db.* TO '$dbuser'@'$dbhost';
	    USE $db;
	    CREATE TABLE CREATE TABLE $dbtable
	    (
		    username    varchar(15),
		    password    varchar(32),
		    role        varchar(15)
	    );
	
	Replace the $ variables with the same ones you have used in config.php

3.	Add users in MySQL
	
	    INSERT INTO $dbtable VALUES ('username','password','role');
	
	REPLACE username and password with those which you will give out to the people involved.
	The allowed values of 'role' are 'med_admin','receptionist','doctor'
