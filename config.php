<?php	
$dbhost = '192.168.0.246';
	$dbuser = 'cron';
	$dbpass = '1234';
	$dbname = 'asterisk';

	@mysql_connect($dbhost,$dbuser,$dbpass) or die("Server is not available, please try again later");
	@mysql_select_db($dbname) or die("Database not available, please try again later");

?>