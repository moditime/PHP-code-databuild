<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_Leads = "192.168.0.4";
$database_Leads = "ABSA";
$username_Leads = "MyPAWeb";
$password_Leads = "c43R79:9d5!4z,T";

// Create connection
$leads = new mysqli($hostname_Leads, $username_Leads, $password_Leads, $database_Leads);
// Check connection
if ($leads->connect_error) {
    die("Connection failed: " . $leads->connect_error);
} 


?>