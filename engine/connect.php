<?php
$username = "root";
$password = "";
$hostname = "localhost"; 
$database = "mvc";

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
mysql_select_db($database,$dbhandle) or die("Unable to select database");

?>