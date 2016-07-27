<?php	
	$tsdbhost = '192.168.0.244';
	$tsdbuser = 'callcentre';
	$tsdbpass = 'fmpfeygo';
	$tsdbname = 'Telesave';

	@mysql_connect($tsdbhost,$tsdbuser,$tsdbpass) or die("Server is not available, please try again later");
	@mysql_select_db($tsdbname) or die("Database not available, please try again later");

?>